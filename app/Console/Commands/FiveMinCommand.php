<?php

namespace App\Console\Commands;

use App\Models\{UserFamily, User, Plan, CronUpdate, CommissionDetail, Commission, PurchasedPlan, GeneralSetting};
use Illuminate\Console\Command;

class FiveMinCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'five:min';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command run every 5 min';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info('This is Five Min command');
        $gnl = GeneralSetting::first();
        $gnl->last_cron = Carbon::now()->toDateTimeString();
		$gnl->save();
        $id = "5min";

        if($id == "5min"){
            
            $crons = CronUpdate::where('status', 0)->get();
            foreach($crons as $cron){
                $cron->status = 1;
                $cron->save();
                if($cron->type == 'purchased_booster'){

                    $user = User::find($cron->user_id);
                    if (!$user) {
                        return ['status' => 'user not found'];
                    }

                    $purchaseCount = $user->purchasedBooster()->count();

                    if ($purchaseCount == 0) {
                        return ['status' => 'no purchase'];
                    }
                    if ($purchaseCount > 1) {
                        return ['status' => 'not first purchase'];
                    }

                    $upliners = [];
                    $currentRefId = $user->ref_id;

                    for ($level = 1; $level <= 6; $level++) {
                        if (!$currentRefId) break;

                        $uplineUser = User::find($currentRefId);

                        $isUnilevel = $uplineUser->purchasedBooster()->count();

                        if (!$uplineUser) break;

                        $upliners[] = [
                            'level'   => $level,
                            'user_id' => $uplineUser->id,
                            'isUnilevel' => ($isUnilevel > 0)? 'yes' : 'no',
                        ];

                        $currentRefId = $uplineUser->ref_id;
                    }
                    foreach ($upliners as $key => $value) {
                        $level = $value['level'];
                        $user_id = $value['user_id'];
                        if($value['isUnilevel'] == "yes"){
                            $commission = Commission::where('status', 1)->first();
                            if($commission){
                                $refs = CommissionDetail::where('commission_id', $commission->id )->get();
                                foreach ($refs as $key => $ref) {
                                    if ($level == $ref->level) {
                                        $percent = $ref->percent;
                                        $booster_id = getBoosterWithAmount($cron->user_id, $cron->amount)->booster_id;
                                        vipUnilevelCommission($cron->user_id, $commission->wallet_id, $percent, $commission->id, $commission->name, $booster_id);
                                    }
                                }
                            }
                        }
                    }
                }
                if($cron->type == 'new_register'){
                    familyTreeAdjust($cron->user_id);
                }
            }
        }
        elseif($id == "unprocessed_data"){
            // return 12345;
            $unprocessedDatas = UnprocessedData::where('is_processed', 0)->get();

            foreach ($unprocessedDatas as $unprocessedData) {

                if (now() >= $unprocessedData->created_at->addHours($unprocessedData->time_period_hours)) {
                    $unprocessedData->update(['is_processed' => 1]);

                    call_user_func_array($unprocessedData->method, json_decode($unprocessedData->data));
                }
            }
        }

        $this->info('Command run every 5 min has been executed successfully');
    }
}
