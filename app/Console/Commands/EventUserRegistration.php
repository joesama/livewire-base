<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use  App\Models\EventRegistration;

class EventUserRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'training:register
//                            {name : Person name}
//                            {email : Person email}
//                            {--notify : Notify Registration Email}';

    protected $signature = 'training:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to register user to event';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $name = $this->ask('What is your name?');
        $email = $this->ask('What is your email?');
        $notify = $this->confirm('Do you wish to get email for registration?', false);

        $session = $this->choice(
            'Which session would you like to attend?',
            ['Storage', 'Event Driven', 'Artisan Console', 'Notification', 'Everything'],
            4,
            $maxAttempts = 3,
            $allowMultipleSelections = true
        );

        $model = EventRegistration::where('email', $email)->first() ?? new  EventRegistration();
        $model->email= $email;
        $model->name= $name;
        $model->session= $session;
        $model->save();

        $this->newLine();
        $this->info('Name : ' . $model->name);
        $this->newLine();
        $this->info('Email : ' . $model->email);
        $this->newLine();
        if ($notify) {
            $this->info('Registration email should be notified.');
        }
        $this->newLine();
        $this->table(
            ['Name', 'Email', 'Notify Email', 'session'],
            [
                [$model->name, $model->email, ($notify ? 'yes' : 'no'), implode(', ', $model->session)]
            ]
        );

        if ($notify) {
            $model->notify(new \App\Notifications\NotifyRegisteredEventUser($model));
        }

        return Command::SUCCESS;
    }

    protected function saveArgument(): void
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        $notify = $this->option('notify');
    }
}
