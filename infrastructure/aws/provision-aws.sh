#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────────────────
# provision-aws.sh — One-time AWS infrastructure provisioning for PrintHub
#
# Prerequisites:
#   - AWS CLI v2 installed and configured (aws configure)
#   - jq installed
#   - Sufficient IAM permissions (ECR, ECS, RDS, ElastiCache, ALB, VPC, SSM,
#     SecretsManager, CloudWatch Logs, IAM)
#
# Usage:
#   export AWS_REGION=eu-west-1
#   export APP_DOMAIN=printhub.example.com
#   bash infrastructure/aws/provision-aws.sh
# ─────────────────────────────────────────────────────────────────────────────

set -euo pipefail

# ── Variables ─────────────────────────────────────────────────────────────────
AWS_REGION="${AWS_REGION:-eu-west-1}"
APP_DOMAIN="${APP_DOMAIN:?Please set APP_DOMAIN}"
PROJECT="printhub"
ENV="${DEPLOY_ENV:-production}"

ACCOUNT_ID=$(aws sts get-caller-identity --query Account --output text)
ECR_REGISTRY="${ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com"

echo "==> Provisioning PrintHub on AWS"
echo "    Region:     $AWS_REGION"
echo "    Account:    $ACCOUNT_ID"
echo "    Domain:     $APP_DOMAIN"
echo "    Environment: $ENV"
echo ""

# ── ECR Repository ─────────────────────────────────────────────────────────────
echo "==> Creating ECR repository..."
aws ecr describe-repositories --repository-names "${PROJECT}-app" \
    --region "$AWS_REGION" &>/dev/null \
  || aws ecr create-repository \
    --repository-name "${PROJECT}-app" \
    --image-scanning-configuration scanOnPush=true \
    --encryption-configuration encryptionType=AES256 \
    --region "$AWS_REGION"

echo "    ECR: ${ECR_REGISTRY}/${PROJECT}-app"

# ── VPC (use default or create a dedicated one) ────────────────────────────────
echo "==> Looking up default VPC..."
VPC_ID=$(aws ec2 describe-vpcs \
  --filters Name=isDefault,Values=true \
  --query 'Vpcs[0].VpcId' --output text --region "$AWS_REGION")
echo "    VPC: $VPC_ID"

SUBNET_IDS=$(aws ec2 describe-subnets \
  --filters Name=vpcId,Values="$VPC_ID" \
  --query 'Subnets[*].SubnetId' --output text --region "$AWS_REGION")
SUBNET_ARRAY=$(echo "$SUBNET_IDS" | tr '\t' ',')
echo "    Subnets: $SUBNET_ARRAY"

# ── Security Groups ────────────────────────────────────────────────────────────
echo "==> Creating security groups..."

ALB_SG=$(aws ec2 describe-security-groups \
  --filters Name=group-name,Values="${PROJECT}-alb-sg" Name=vpc-id,Values="$VPC_ID" \
  --query 'SecurityGroups[0].GroupId' --output text --region "$AWS_REGION" 2>/dev/null || echo "None")

if [ "$ALB_SG" = "None" ] || [ -z "$ALB_SG" ]; then
  ALB_SG=$(aws ec2 create-security-group \
    --group-name "${PROJECT}-alb-sg" \
    --description "PrintHub ALB security group" \
    --vpc-id "$VPC_ID" \
    --query GroupId --output text --region "$AWS_REGION")
  aws ec2 authorize-security-group-ingress --group-id "$ALB_SG" \
    --protocol tcp --port 80  --cidr 0.0.0.0/0 --region "$AWS_REGION"
  aws ec2 authorize-security-group-ingress --group-id "$ALB_SG" \
    --protocol tcp --port 443 --cidr 0.0.0.0/0 --region "$AWS_REGION"
fi
echo "    ALB SG: $ALB_SG"

APP_SG=$(aws ec2 describe-security-groups \
  --filters Name=group-name,Values="${PROJECT}-app-sg" Name=vpc-id,Values="$VPC_ID" \
  --query 'SecurityGroups[0].GroupId' --output text --region "$AWS_REGION" 2>/dev/null || echo "None")

if [ "$APP_SG" = "None" ] || [ -z "$APP_SG" ]; then
  APP_SG=$(aws ec2 create-security-group \
    --group-name "${PROJECT}-app-sg" \
    --description "PrintHub App (PHP-FPM) security group" \
    --vpc-id "$VPC_ID" \
    --query GroupId --output text --region "$AWS_REGION")
  aws ec2 authorize-security-group-ingress --group-id "$APP_SG" \
    --protocol tcp --port 9000 --source-group "$ALB_SG" --region "$AWS_REGION"
fi
echo "    App SG: $APP_SG"

# ── CloudWatch Log Groups ─────────────────────────────────────────────────────
echo "==> Creating CloudWatch log groups..."
for group in "/ecs/${PROJECT}-backend" "/ecs/${PROJECT}-queue"; do
  aws logs create-log-group --log-group-name "$group" \
    --region "$AWS_REGION" 2>/dev/null || true
  aws logs put-retention-policy --log-group-name "$group" \
    --retention-in-days 30 --region "$AWS_REGION"
done

# ── IAM Roles ─────────────────────────────────────────────────────────────────
echo "==> Creating IAM roles..."

EXECUTION_ROLE_ARN=$(aws iam get-role \
  --role-name "${PROJECT}-ecs-execution-role" \
  --query Role.Arn --output text 2>/dev/null || echo "")

if [ -z "$EXECUTION_ROLE_ARN" ]; then
  EXECUTION_ROLE_ARN=$(aws iam create-role \
    --role-name "${PROJECT}-ecs-execution-role" \
    --assume-role-policy-document '{
      "Version":"2012-10-17",
      "Statement":[{
        "Effect":"Allow",
        "Principal":{"Service":"ecs-tasks.amazonaws.com"},
        "Action":"sts:AssumeRole"
      }]
    }' \
    --query Role.Arn --output text)
  aws iam attach-role-policy \
    --role-name "${PROJECT}-ecs-execution-role" \
    --policy-arn arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy
  aws iam attach-role-policy \
    --role-name "${PROJECT}-ecs-execution-role" \
    --policy-arn arn:aws:iam::aws:policy/AmazonSSMReadOnlyAccess
fi
echo "    Execution Role: $EXECUTION_ROLE_ARN"

TASK_ROLE_ARN=$(aws iam get-role \
  --role-name "${PROJECT}-ecs-task-role" \
  --query Role.Arn --output text 2>/dev/null || echo "")

if [ -z "$TASK_ROLE_ARN" ]; then
  TASK_ROLE_ARN=$(aws iam create-role \
    --role-name "${PROJECT}-ecs-task-role" \
    --assume-role-policy-document '{
      "Version":"2012-10-17",
      "Statement":[{
        "Effect":"Allow",
        "Principal":{"Service":"ecs-tasks.amazonaws.com"},
        "Action":"sts:AssumeRole"
      }]
    }' \
    --query Role.Arn --output text)
  # Allow S3 access for file storage — scoped to the application bucket only
  cat > /tmp/s3-policy.json <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "s3:GetObject",
        "s3:PutObject",
        "s3:DeleteObject",
        "s3:ListBucket"
      ],
      "Resource": [
        "arn:aws:s3:::${BUCKET_NAME}",
        "arn:aws:s3:::${BUCKET_NAME}/*"
      ]
    }
  ]
}
EOF
  aws iam put-role-policy \
    --role-name "${PROJECT}-ecs-task-role" \
    --policy-name "${PROJECT}-s3-access" \
    --policy-document file:///tmp/s3-policy.json
fi
echo "    Task Role: $TASK_ROLE_ARN"

# ── ECS Cluster ────────────────────────────────────────────────────────────────
echo "==> Creating ECS cluster..."
aws ecs describe-clusters --clusters "${PROJECT}-cluster" \
    --region "$AWS_REGION" \
    --query "clusters[?status=='ACTIVE'].clusterName" \
    --output text | grep -q "${PROJECT}-cluster" \
  || aws ecs create-cluster \
    --cluster-name "${PROJECT}-cluster" \
    --capacity-providers FARGATE FARGATE_SPOT \
    --default-capacity-provider-strategy \
      capacityProvider=FARGATE,weight=1,base=1 \
    --region "$AWS_REGION"
echo "    Cluster: ${PROJECT}-cluster"

# ── S3 Bucket ─────────────────────────────────────────────────────────────────
BUCKET_NAME="${PROJECT}-storage-${ACCOUNT_ID}"
echo "==> Creating S3 bucket ($BUCKET_NAME)..."
aws s3api head-bucket --bucket "$BUCKET_NAME" --region "$AWS_REGION" 2>/dev/null \
  || aws s3api create-bucket \
    --bucket "$BUCKET_NAME" \
    --region "$AWS_REGION" \
    --create-bucket-configuration LocationConstraint="$AWS_REGION"
aws s3api put-bucket-versioning \
  --bucket "$BUCKET_NAME" \
  --versioning-configuration Status=Enabled
aws s3api put-public-access-block \
  --bucket "$BUCKET_NAME" \
  --public-access-block-configuration \
    BlockPublicAcls=true,IgnorePublicAcls=true,BlockPublicPolicy=true,RestrictPublicBuckets=true
echo "    Bucket: $BUCKET_NAME"

# ── SSM Parameter Store — non-secret values ────────────────────────────────────
echo "==> Storing configuration in SSM Parameter Store..."
aws ssm put-parameter --name "/${PROJECT}/AWS_BUCKET" \
  --value "$BUCKET_NAME" --type String --overwrite --region "$AWS_REGION"
aws ssm put-parameter --name "/${PROJECT}/AWS_DEFAULT_REGION" \
  --value "$AWS_REGION" --type String --overwrite --region "$AWS_REGION"
aws ssm put-parameter --name "/${PROJECT}/DB_CONNECTION" \
  --value "mysql" --type String --overwrite --region "$AWS_REGION"

echo ""
echo "==> Infrastructure provisioned successfully!"
echo ""
echo "Next steps:"
echo "  1. Create an RDS MySQL 8.4 instance and store DB_HOST in SSM."
echo "  2. Create an ElastiCache Redis cluster and store REDIS_HOST in SSM."
echo "  3. Store sensitive secrets (DB_PASSWORD, REDIS_PASSWORD, APP_KEY, MAIL_PASSWORD)"
echo "     in AWS Secrets Manager under the '/${PROJECT}/' prefix."
echo "  4. Register ECS task definitions:"
echo "     aws ecs register-task-definition --cli-input-json file://infrastructure/aws/ecs-task-backend.json"
echo "     aws ecs register-task-definition --cli-input-json file://infrastructure/aws/ecs-task-queue.json"
echo "  5. Create ECS services pointing to the task definitions."
echo "  6. Set up an Application Load Balancer (ALB) with ACM certificate for HTTPS."
echo "  7. Create a Route 53 hosted zone for $APP_DOMAIN and point it to the ALB."
echo ""
echo "GitHub Actions secrets to configure:"
echo "  AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY"
echo "  ECS_SUBNET_ID=$( echo "$SUBNET_ARRAY" | cut -d',' -f1 )"
echo "  ECS_SECURITY_GROUP_ID=$APP_SG"
