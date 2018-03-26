<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'App Initialization';

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
        $this->info('Starting App Initialization...');
        $this->info('Review this installation process in \App\Console\Commands\AppInit.php');

        sleep(3);

        $companyName = 'Toko Baru';
        $userName = 'Admin';
        $userEmail = 'admin@tkbaru.com';
        $userPassword = 'thepassword';

        $valid = false;

        while (!$valid) {
            $companyName = $this->ask('Company Name:', $companyName);
            $userName = $this->ask('Name:', $userName);
            $userEmail = $this->ask('Email:', $userEmail);
            $userPassword = $this->secret('Password:', $userPassword);

            $validator = Validator::make([
                'company' => $companyName,
                'name' => $userName,
                'email' => $userEmail,
                'password' => $userPassword
            ], [
                'company' => 'required|min:3|max:100',
                'name' => 'required|min:3|max:50',
                'email' => 'required|max:255|email|unique:users,email',
                'password' => 'required|min:7'
            ]);

            if (!$validator->fails()) {
                $valid = true;
            } else {
                foreach ($validator->errors()->all() as $errorMessage) {
                    $this->error($errorMessage);
                }
            }
        }

        $confirmed = $this->confirm("Everything's OK? Do you wish to continue?");

        if (!$confirmed) {
            $this->error('Aborted');
            return false;
        }

        sleep(3);

        $this->info('Done!');
    }
}
