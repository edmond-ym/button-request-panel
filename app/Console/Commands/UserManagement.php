<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Library\Services\SubscriptionManagementService;
use App\Http\Controllers\SubscriptionManagementController;
class UserManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:user-management';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage A User';

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
        $running=true;
        
        while($running){
            $action = $this->choice(
                'Choose your Option:',
                ['UserInfo', 'Exit'],
               
            );
            if ($action=="UserInfo") {
                $email = $this->ask('What is your email?');
                //$bar = $this->output->createProgressBar(2);
                //$bar->start();
                $UserData=User::where('email', '=', $email)->get();
                
                if (count($UserData)==0) {
                    $this->error('Email Not Exist');
                }else{
                    
                    $userId=$UserData[0]->id;
                    $this->table(
                        ['Name', 'Email', 'Stripe ID'],
                        User::select('name', 'email', 'stripe_id')->where('email', '=', $email)->get()
                    );
                    //$bar->advance();
                    if (SubscriptionManagementService::subscribed($userId)) {
                        $SubscriptionData=SubscriptionManagementService::currentSubscriptionData($userId);
                        $subscriptionItemList=SubscriptionManagementService::subscriptionItemList($userId, $SubscriptionData);

                        for ($i=0; $i < count($subscriptionItemList); $i++) { 
                            # code...
                            $this->table(
                                ['Plan', 'Interval', 'Active'],
                                [[$subscriptionItemList[$i]->plan->nickname, 
                                  $subscriptionItemList[$i]->plan->interval,
                                  $subscriptionItemList[$i]->plan->active ? 'True':'False'
                                ]]
                            );
                        }

                    }
                    //$bar->finish();
                    
                    
                    

                }
               
            }
            if ($action=="Exit") {
                break;

            }

            
            

        }
        $this->info('Finished!');
        return 0;
    }
}





