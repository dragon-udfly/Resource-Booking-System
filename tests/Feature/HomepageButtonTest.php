<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageButtonTest extends TestCase
{
    /**
     * Test the System Login button.
     *
     * @return void
     */
    public function test_system_login_button_redirects_correctly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test the Book Hall button.
     *
     * @return void
     */
    public function test_book_hall_button_redirects_correctly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get(route('halls.schedule'));
        $response->assertStatus(200);
    }

    /**
     * Test the Book Quarters button.
     *
     * @return void
     */
    public function test_book_quarters_button_redirects_correctly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get(route('quarterapplication'));
        $response->assertStatus(200);
    }

    /**
     * Test the Developers link.
     *
     * @return void
     */
    public function test_developers_link_redirects_correctly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/developers');
        $response->assertStatus(200);
    }
}
