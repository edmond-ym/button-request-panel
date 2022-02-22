<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class OfflineSubscriptionStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:offline-subscription-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh offline subscription status for each user!';

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
        
        $all=User::all();
    
        for ($i=0; $i < count($all); $i++) { 
            $userId=$all[$i]->id;
            $name=$all[$i]->name;
            $email=$all[$i]->email;

            $r=User::where('id', '=', $userId)->update(['subscription_status_updated'=>'false']);
            if ($r) {
                $this->info('Successfully updating User #'.$userId.' Name: '.$name. ' Email: '.$email);
            } else {
                $this->error('Failed updating User #'.$userId.' Name: '.$name. ' Email: '.$email);
            }
            
        }
        return 0;
    }
}
