<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autodialer:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an Administrator account';

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
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask('What is the administrator\'s name?');
        $email = $this->ask('What is the administrator\'s email?');
        $password = $this->secret('What is the administrator\'s password?');

        $role = \App\Models\Role::firstOrCreate([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'I am the Lizard King, I can do anything!'
        ]);

        $user = \App\User::firstOrNew([
            'email' => $email
            ]);
        
        $user->name = $name;
        $user->password = bcrypt($password);
        $user->save();

        if (!$user->hasRole('admin')) {
            $user->attachRole($role->id);
        }

        $this->info('Admin user created!');
    }
}
