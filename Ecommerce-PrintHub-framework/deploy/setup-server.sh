#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────
# PrintHub – Initial server setup script
#
# Run ONCE on a fresh EC2 instance (Amazon Linux 2023 / Ubuntu).
# Prerequisites: the instance must have an IAM role that allows
#   ecr:GetAuthorizationToken and ecr:BatchGetImage.
#
# Usage:
#   chmod +x deploy/setup-server.sh
#   ./deploy/setup-server.sh --domain your-domain.com --email admin@your-domain.com
# ──────────────────────────────────────────────────────────────
set -euo pipefail

DOMAIN=""
EMAIL=""
DEPLOY_DIR="/opt/printhub"

usage() {
    echo "Usage: $0 --domain <domain> --email <certbot-email>"
    exit 1
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --domain) DOMAIN="$2"; shift 2 ;;
        --email)  EMAIL="$2";  shift 2 ;;
        *)        usage ;;
    esac
done

[[ -z "$DOMAIN" || -z "$EMAIL" ]] && usage

echo "===> Installing Docker..."
if command -v apt-get &>/dev/null; then
    # Ubuntu / Debian
    apt-get update -y
    apt-get install -y ca-certificates curl gnupg lsb-release awscli
    install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
        | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
        https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" \
        | tee /etc/apt/sources.list.d/docker.list > /dev/null
    apt-get update -y
    apt-get install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
else
    # Amazon Linux 2023
    dnf install -y docker aws-cli
    systemctl enable --now docker
fi

usermod -aG docker "${SUDO_USER:-ec2-user}" || true

echo "===> Creating deployment directory ${DEPLOY_DIR}..."
mkdir -p "${DEPLOY_DIR}"
cd "${DEPLOY_DIR}"

echo "===> Copying compose and nginx files..."
# These files must be present in the same directory as this script.
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cp "${SCRIPT_DIR}/../docker-compose.prod.yml" .
cp -r "${SCRIPT_DIR}/../nginx" .

echo "===> Substituting domain in nginx/proxy.conf..."
sed -i "s/<YOUR_DOMAIN>/${DOMAIN}/g" nginx/proxy.conf

echo "===> Obtaining Let's Encrypt certificate (dry-run first)..."
# Start a temporary HTTP-only nginx to serve the ACME challenge
docker run --rm -d \
    --name certbot-nginx \
    -p 80:80 \
    -v "$(pwd)/certbot_www:/var/www/certbot" \
    nginx:1.27-alpine \
    sh -c "mkdir -p /var/www/certbot && nginx -g 'daemon off;' \
           -c /dev/stdin <<'EOF'
events {}
http {
  server {
    listen 80;
    location /.well-known/acme-challenge/ { root /var/www/certbot; }
    location / { return 200 'ok'; }
  }
}
EOF"

sleep 3

docker run --rm \
    -v "$(pwd)/certbot_www:/var/www/certbot" \
    -v "$(pwd)/certbot_certs:/etc/letsencrypt" \
    certbot/certbot certonly \
        --webroot \
        --webroot-path /var/www/certbot \
        --email "${EMAIL}" \
        --agree-tos \
        --no-eff-email \
        -d "${DOMAIN}" \
        -d "www.${DOMAIN}"

docker stop certbot-nginx || true

echo ""
echo "===> Certificate obtained successfully."
echo "===> Next steps:"
echo "     1. Copy your .env (based on .env.production.example) to ${DEPLOY_DIR}/.env"
echo "     2. Set APP_IMAGE / APP_TAG in the .env or export them before running:"
echo "           docker compose -f docker-compose.prod.yml up -d"
echo "     3. The CI/CD pipeline will handle future deployments automatically."
