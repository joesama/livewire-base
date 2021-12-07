<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\EventRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EventUserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_event_registration_access()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get('/registration/new');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_event_registration_submission()
    {
        $user = User::factory()->create();

        $name = 'Ali Baba';
        $email = 'ali.bab@alibaba.com';
        $filename = now()->format('His') . 'avatar.jpg';


        Storage::fake('book');
        $file = UploadedFile::fake()->image($filename);

        $response = $this->actingAs($user, 'web')->post(
            route('registration.store'),
            [
                'email' =>  $email,
                'name' => $name,
                'upload' => $file,
            ]
        );

        $model = EventRegistration::where('email', $email)->first();

        $response->assertStatus(200)
            ->assertExactJson($model->toArray())
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('name', $name)
                    ->has('avatar')
                    ->has('session')
                    ->whereType('name', 'string|null')
                    ->etc()
            );

        Storage::disk('public')->assertExists('/book/' . $filename);

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_event_registration_console()
    {
        $name = 'Ali Baba';

        $email = 'ali.bab@alibaba.com';

        $this->artisan('training:register')
            ->expectsQuestion('What is your name?', $name)
            ->expectsQuestion('What is your email?',  $email)
            ->expectsConfirmation('Do you wish to get email for registration?', 'yes')
            ->expectsQuestion('Which session would you like to attend?', 'Artisan Console')
            ->assertExitCode(0);

    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_event_registration_model()
    {
        $participants = EventRegistration::factory()
            ->count(5)
            ->state(new Sequence( // alternate admin field
                ['session' => 'Storage'],
                ['session' => 'Event Driven'],
            ))->create();

        foreach ($participants as $part) {
            $this->assertModelExists($part);
        }

        $this->assertDatabaseCount('event_registrations', 5);

        $this->assertDatabaseHas('event_registrations', [
            'session' => 'Storage',
        ]);

        $this->assertDatabaseMissing('event_registrations', [
            'email' => 'sally@example.com',
        ]);

        $lastParticipant = $participants->last();

        $lastParticipant->delete();

        $this->assertDeleted($lastParticipant);
    }
}
