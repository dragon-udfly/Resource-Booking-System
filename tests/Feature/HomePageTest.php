<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * Test the Home page (/) CSS and content.
     */
    public function test_home_page_renders_correctly_with_css()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Verify key content
        $response->assertSee('Welcome to the Hall and Quarters Booking System');
        $response->assertSee('Please log in to approve hall booking and quarter reservation applications.');

        // Verify CSS Classes validity
        $response->assertSee('class="content-area"', false);
        $response->assertSee('class="main-title"', false);
        $response->assertSee('class="sub-text"', false);
        $response->assertSee('class="action-button"', false);

        // Verify Inline CSS presence (looking for unique style definitions)
        $response->assertSee('.content-area {', false);
        $response->assertSee('text-align: center;', false);
        $response->assertSee('.main-title {', false);
        $response->assertSee('color: rgb(6, 4, 60);', false);
        $response->assertSee('.action-button {', false);
        $response->assertSee('background-color: #007bff;', false);
    }

    /**
     * Test the Homepage (/homepage) CSS and content.
     */
    public function test_homepage_renders_correctly_with_css()
    {
        $response = $this->get(route('homepage'));

        $response->assertStatus(200);

        // Verify key content
        $response->assertSee('Welcome to the Hall and Quarters Booking System');
        $response->assertSee('Hall Booking and Quarter Reservation Applications and Overviews.');

        // Verify CSS Classes validity
        $response->assertSee('class="content-area"', false);
        $response->assertSee('class="main-title"', false);
        $response->assertSee('class="sub-text"', false);
        $response->assertSee('class="action-button"', false);

        // Verify Inline CSS presence
        $response->assertSee('.content-area {', false);
        $response->assertSee('.main-title {', false);
        $response->assertSee('.action-button:hover {', false);
    }
}
