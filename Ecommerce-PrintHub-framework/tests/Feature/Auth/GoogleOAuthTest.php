<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleOAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_route_is_accessible_for_guests(): void
    {
        $response = $this->get(route('auth.google'));

        // Socialite redirects to Google (3xx) or to login if already auth
        $response->assertRedirect();
    }

    public function test_new_user_is_created_and_logged_in_via_google(): void
    {
        $googleUser = Mockery::mock(SocialiteUser::class);
        $googleUser->shouldReceive('getId')->andReturn('google-uid-123');
        $googleUser->shouldReceive('getName')->andReturn('John Doe');
        $googleUser->shouldReceive('getEmail')->andReturn('john@example.com');

        $provider = Mockery::mock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->shouldReceive('user')->andReturn($googleUser);

        $socialite = Mockery::mock(SocialiteFactory::class);
        $socialite->shouldReceive('driver')->with('google')->andReturn($provider);

        $this->app->instance(SocialiteFactory::class, $socialite);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'google_id' => 'google-uid-123',
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_existing_google_user_is_logged_in_without_duplicate(): void
    {
        $existingUser = User::factory()->create([
            'google_id' => 'google-uid-456',
            'email' => 'jane@example.com',
            'name' => 'Jane Doe',
        ]);

        $googleUser = Mockery::mock(SocialiteUser::class);
        $googleUser->shouldReceive('getId')->andReturn('google-uid-456');
        $googleUser->shouldReceive('getName')->andReturn('Jane Doe Updated');
        $googleUser->shouldReceive('getEmail')->andReturn('jane@example.com');

        $provider = Mockery::mock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->shouldReceive('user')->andReturn($googleUser);

        $socialite = Mockery::mock(SocialiteFactory::class);
        $socialite->shouldReceive('driver')->with('google')->andReturn($provider);

        $this->app->instance(SocialiteFactory::class, $socialite);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($existingUser->fresh());

        // No duplicate user created
        $this->assertSame(1, User::where('google_id', 'google-uid-456')->count());
    }

    public function test_existing_email_user_gets_google_id_linked(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'alice@example.com',
            'google_id' => null,
        ]);

        $googleUser = Mockery::mock(SocialiteUser::class);
        $googleUser->shouldReceive('getId')->andReturn('google-uid-789');
        $googleUser->shouldReceive('getName')->andReturn('Alice');
        $googleUser->shouldReceive('getEmail')->andReturn('alice@example.com');

        $provider = Mockery::mock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->shouldReceive('user')->andReturn($googleUser);

        $socialite = Mockery::mock(SocialiteFactory::class);
        $socialite->shouldReceive('driver')->with('google')->andReturn($provider);

        $this->app->instance(SocialiteFactory::class, $socialite);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($existingUser->fresh());

        // Existing account now has google_id linked, no duplicate created
        $this->assertSame(1, User::where('email', 'alice@example.com')->count());
        $this->assertDatabaseHas('users', [
            'email' => 'alice@example.com',
            'google_id' => 'google-uid-789',
        ]);
    }

    public function test_invalid_oauth_state_redirects_to_login_with_error(): void
    {
        $provider = Mockery::mock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->shouldReceive('user')->andThrow(new InvalidStateException());

        $socialite = Mockery::mock(SocialiteFactory::class);
        $socialite->shouldReceive('driver')->with('google')->andReturn($provider);

        $this->app->instance(SocialiteFactory::class, $socialite);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_page_contains_google_button(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee(route('auth.google'));
        $response->assertSee('Continue with Google');
    }
}
