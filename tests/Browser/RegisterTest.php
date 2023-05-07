<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Unit Test for Register with 2FA
     * @group Register
     */

    public function test_register_with_user_already_exist()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/register')
                ->type('name', $user->name)
                ->type('email', $user->email)
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')
		        ->assertPathIs('/register')
                ->assertSee('The email has already been taken.')
                ->pause(5000);
        });
    }

    public function test_register_with_minimal_password()
    {
        $user = User::factory()->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/register')
                ->type('name', $user->name)
                ->type('email', $user->email)
                ->type('password', '1234')
                ->type('password_confirmation', '1234')
                ->press('Register')
		        ->assertPathIs('/register')
                ->assertSee('The password field must be at least 6 characters.')
                ->pause(5000);
        });
    }

    public function test_register_with_password_field_confirmation()
    {
        $user = User::factory()->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/register')
                ->type('name', $user->name)
                ->type('email', $user->email)
                ->type('password', 'password')
                ->type('password_confirmation', '1234')
                ->press('Register')
		        ->assertPathIs('/register')
                ->assertSee('The password field confirmation does not match.')
                ->pause(5000);
        });
    }

    // public function test_register_new_user_with_2fa()
    // {
    //     $user = User::factory()->make();

    //     $this->browse(function (Browser $browser) use ($user) {
    //         $browser->visit('/register')
    //             ->type('name', $user->name)
    //             ->type('email', $user->email)
    //             ->type('password', 'password')
    //             ->type('password_confirmation', 'password')
    //             ->press('Register')
    //             ->waitForLocation('/register')
    //             ->clickLink('Complete Registration')
    //             ->waitForLocation('/home')
    //             ->type('one_time_password', app('pragmarx.google2fa')->getCurrentOtp($user->google2fa_secret))
    //             ->press('Login')
	// 	        ->assertPathIs('/email/verify')
    //             ->pause(5000);

    //         $browser->logout();
    //     });
    // }
}
