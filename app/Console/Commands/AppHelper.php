<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:helper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'App Helper';

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
        $this->info('Available Helper:');
        $this->info('[0] ');
        $this->info('[1] ');
        $this->info('[2] ');
        $this->info('[3] ');
        $this->info('[4] ');
        $this->info('[5] ');
        $this->info('[6] ');
        $this->info('[7] ');
        $this->info('[8] ');
        $this->info('[9] ');

        $choose = $this->ask('Choose Helper');

        switch ($choose) {
            case 0:

                break;
            case 1:

                break;
            case 2:

                break;
            case 3:

                break;
            case 3:

                break;
            case 4:

                break;
            case 5:

                break;
            case 6:

                break;
            case 7:

                break;
            case 8:

                break;
            case 9:

                break;
            default:
                break;
        }

        sleep(3);
        $this->info('Done!');
    }
}
