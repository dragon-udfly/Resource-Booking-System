<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Memo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class MemoSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $sender;
    protected $receiver;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two temporary users for testing
        $this->sender = User::create([
            'user_id' => 'TEST-SENDER-' . uniqid(),
            'first_name' => 'Test',
            'last_name' => 'Sender',
            'email' => 'sender@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'designation' => 'Officer A',
            'nic_number' => 'NIC-S-' . uniqid(),
            'contact_number' => '071' . rand(1000000, 9999999),
            'passcode' => '1234',
            'created_datetime' => now(),
        ]);

        $this->receiver = User::create([
            'user_id' => 'TEST-RECEIVER-' . uniqid(),
            'first_name' => 'Test',
            'last_name' => 'Receiver',
            'email' => 'receiver@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'designation' => 'Officer B',
            'nic_number' => 'NIC-R-' . uniqid(),
            'contact_number' => '071' . rand(1000000, 9999999),
            'passcode' => '5678',
            'created_datetime' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        // RefreshDatabase handles cleanup
        parent::tearDown();
    }

    /**
     * Test the full lifecycle of a memo.
     */
    public function test_memo_lifecycle()
    {
        // 1. Send Memo (User A to User B)
        $this->actingAs($this->sender);

        $memoData = [
            'receiver_id' => $this->receiver->user_id,
            'subject' => 'Confidential Test Subject',
            'body' => 'This is a private test memo body.',
        ];

        $response = $this->post(route('memo.send'), $memoData);
        $response->assertRedirect(route('memo.index'));
        $response->assertSessionHas('success', 'Memo sent successfully!');

        // Verify record exists and is encrypted in DB
        $memo = Memo::where('sender_id', $this->sender->user_id)->first();
        $this->assertNotNull($memo);

        // Check raw DB values are different from input (encrypted)
        // We use query builder to get raw values without accessors
        $rawMemo = \DB::table('memos')->where('id', $memo->id)->first();
        $this->assertNotEquals($memoData['subject'], $rawMemo->subject);
        $this->assertNotEquals($memoData['body'], $rawMemo->body);

        // 2. Receiver views inbox
        $this->actingAs($this->receiver);
        $response = $this->get(route('memo.index'));
        $response->assertStatus(200);
        $response->assertSee('Confidential Test Subject'); // Accessor should decrypt

        // 3. Receiver responds to memo
        $response = $this->post(route('memo.respond', $memo->id), ['status' => 1]); // 1 = Yes/Agreed
        $response->assertJson(['success' => true]);

        // Verify status change
        $memo->refresh();
        $this->assertEquals(1, $memo->status);

        // 4. Receiver clears inbox
        $response = $this->post(route('memo.clear_read'));
        $response->assertJson(['success' => true]);

        $memo->refresh();
        $this->assertEquals(1, $memo->receiver_cleared);

        // 5. Sender clears outbox
        $this->actingAs($this->sender);
        $response = $this->post(route('memo.clear_sent'));
        $response->assertJson(['success' => true]);

        $memo->refresh();
        $this->assertEquals(1, $memo->sender_cleared);

        // 6. Admin cleanup (Final physical deletion)
        // We need an admin user for this
        $admin = User::create([
            'user_id' => 'TEST-ADMIN-' . uniqid(),
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'designation' => 'Admin',
            'nic_number' => 'NIC-A-' . uniqid(),
            'contact_number' => '071' . rand(1000000, 9999999),
            'passcode' => '9999',
            'created_datetime' => now(),
        ]);

        $this->actingAs($admin);
        $response = $this->delete(route('memos.clearResponded'));
        $response->assertRedirect();

        // Verify physical deletion
        $this->assertNull(Memo::find($memo->id));

        $admin->delete();
    }

    /**
     * Test authorization - only sender/receiver can view.
     */
    public function test_memo_authorization()
    {
        // 1. Sender sends to Receiver
        $memo = Memo::create([
            'sender_id' => $this->sender->user_id,
            'receiver_id' => $this->receiver->user_id,
            'subject' => 'Secret',
            'body' => 'Secret Body',
            'status' => 2
        ]);

        // 2. Third party tries to view
        $thirdParty = User::create([
            'user_id' => 'TEST-THIRD-' . uniqid(),
            'first_name' => 'Third',
            'last_name' => 'Party',
            'email' => 'third@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'designation' => 'Stranger',
            'nic_number' => 'NIC-T-' . uniqid(),
            'contact_number' => '071' . rand(1000000, 9999999),
            'passcode' => '0000',
            'created_datetime' => now(),
        ]);

        $this->actingAs($thirdParty);
        $response = $this->get(route('memo.show', $memo->id));
        $response->assertStatus(403);

        $thirdParty->delete();
    }
}
