<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PurchasedBooster;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use App\Models\StakeToken;
use App\Models\Transaction;
use App\Models\MinningHistory;

class MinningController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function minning()
    {
        $page_title = 'Token Minning Station';
        $empty_message = 'No Page found';
        $tokenWallet = UserWallet::with('wallet')->where("user_id",Auth::id())->where('wallet_id',3)->first();
        $token_stake = StakeToken::where('status',"1")->where("user_id",Auth::id())->first();

        $isBoosterPurchase = PurchasedBooster::with("booster")->where("user_id",Auth::id())->where("is_expired","0")->get();

        $tap   = 0;
        $power = 0;
        $days  = 0 ;
        if(count($isBoosterPurchase) > 0){
            $tap = 0;
            $power = 0;
            foreach ($isBoosterPurchase as $key => $value) {
                $booster_Check = $value->booster;

                if($booster_Check->title == "Speed"){
                    $features = unserialize($booster_Check->features);

                    $tap = "1";

                    preg_match('/([\d\.]+)%/', $features[0], $matchPower);
                    preg_match('/\d+/', $features[2], $matchDays);
                    $power = isset($matchPower[1]) ? floatval($matchPower[1]) : 0.0;
                    $days = isset($matchDays[0]) ? floatval($matchDays[0]) : 0.0;
                }
            }
        }
        else{
            $tap   = "1";
            $power = "0.5";
            $days  = "500" ;
        }
        if (isset($token_stake->start_date)) {
            $startDate = Carbon::parse($token_stake->start_date);
            $stakeDaysPassed = $startDate->diffInDays(Carbon::now());
        }
        else{
            $stakeDaysPassed = "0";
        }
        $stakeDaysRemaining = $days - $stakeDaysPassed ; 

        $miningHistory = MinningHistory::where('user_id', Auth::id());
        $histories = $miningHistory->get();
        
        $mining = $miningHistory->latest()->first();
        

        $startDate = optional($mining)->start_date;
        $sessionDuration = (24/$mining->taps) * 3600; 

        $totalTokenEarned = $miningHistory->sum('token_earned');

        $todayEarning = $miningHistory->where('start_date',Carbon::today())->first();

         $totalSeconds = 0;
        foreach ($histories as $history) {
            $start = Carbon::parse($history->start_date);
            $tap = $history->taps;
            $sessionDurationInSeconds = (24 / $tap) * 3600;
            
            $totalSeconds += $sessionDurationInSeconds;
            
        }

        $days = floor($totalSeconds / 86400);
        $hours = floor(($totalSeconds % 86400) / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);

        $tokenMiningTime = '';

        if ($days > 0) {
            $tokenMiningTime .= "{$days}Day ";
        }

        if ($hours > 0 || $days > 0) {
            $tokenMiningTime .= "{$hours}H ";
        }

        $tokenMiningTime .= "{$minutes}Min";

        if($stakeDaysRemaining == 0){
            $user = Auth::user();
            $wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', "3")->firstOrFail(); // adjust based on your model relationship
             
            $cxAmount = $totalTokenEarned;
            
            $trx = getTrx();
            
            $details = 'Token minning id completed...';
            updateWallet($user->id, $trx, '3', NULL, '+', getAmount($cxAmount), $details , 0, 'minning_completed', NULL,'');

            StakeToken::where('status',"1")->where("user_id",Auth::id())->update(['status'=>'0']);
        }

        
        $data = [
            "tap" => $tap,
            "power" => $power,
            "days" => $days,
            "token_minned" => $token_stake->stake_amount ?? 0,
            "stakeDaysRemaining" => $stakeDaysRemaining,
        ];
        return view($this->activeTemplate . 'user.minning.index', compact('page_title',
            'empty_message',
            'tokenWallet',
            'token_stake' ,
            'data',
            'startDate',
            'sessionDuration',
            'mining',
            'totalTokenEarned',
            'todayEarning',
            'tokenMiningTime'
        ));
    }

    public function stakeToken(Request $request){
        
        $this->validate($request, [
            'stake_token'  => 'required',
        ]);

        if($request->stake_token <= 0){
            $notify[] = ['error', 'Insufficient Token to stake'];
            return back()->withNotify($notify);
        }
        $notify[] = ['success','CX token is on stake'];
        $details = 'CX token is on stake';
        $user_id = Auth::id();
        $trx = getTrx();

        $user_wallet = UserWallet::where('user_id', $user_id)->where('wallet_id', "3")->firstOrFail();

        $amount = $request->stake_token;

        $data = [
            "user_id"=> $user_id,
            "stake_amount"=> $amount,
            "start_date" => Carbon::now(),
            "status" =>  "1",
        ];
        
        updateWallet($user_id, $trx, $user_wallet->wallet_id, NULL, '-', getAmount($amount), $details , 0, 'user_staking', NULL,'');

        StakeToken::create($data);


        return redirect()->route('user.minning')->withNotify($notify);
    }

    public function tokenReport(){
        
        $page_title = 'User Report';
        $stakeTokenHistory = StakeToken::where('user_id', Auth::id())->paginate(getPaginate());

        $empty_message = 'No transactions.';
        return view($this->activeTemplate .'Report.index', compact('page_title', 'stakeTokenHistory', 'empty_message'));
    }

    public function minningHistory(Request $request){

        $tokenEarned = $request->data['tokenMinned'] * ($request->data['power'] / 100);
        $user_id = Auth::id();
        $data = [
            "user_id"  =>$user_id ,
            "token_mined"  =>$request->data['tokenMinned'] ,
            "power"  =>$request->data['power'] ,
            "taps"  =>$request->data['tap'] ,
            "start_date"  =>Carbon::now() ,
            "token_earned"  =>$tokenEarned ,
        ];
        MinningHistory::create($data);
        $notify[] = ['success','Minning Started'];
        return redirect()->route('user.minning')->withNotify($notify);
    }

    public function stakingHistory(){
        $page_title = 'Staking History';
        $stakeTokenHistory = StakeToken::where('user_id', Auth::id())->paginate(getPaginate());

        return view($this->activeTemplate .'user.minning.token_stake_history', compact('page_title', 'stakeTokenHistory'));
    }

    public function swapToken(Request $request){
        $request->validate([
            'amount' => 'required|numeric|min:0.0001',
        ]);

        $user = Auth::user();
        $wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', "3")->firstOrFail(); // adjust based on your model relationship
        
        $rate = 1; 
        $cxAmount = $request->amount;
        $usdtAmount = $cxAmount * $rate;
        
        $trx = getTrx();

        if ($wallet->balance < $cxAmount) {
            return back()->with('error', 'Insufficient CX balance.');
        }
        $wallet->balance -= $cxAmount;
        $wallet->save();
        
        $details = 'CX token is Swapped with USDT...';
        updateWallet($user->id, $trx, '1', NULL, '+', getAmount($cxAmount), $details , 0, 'Token is Swapped to USDT', NULL,'');
        
        return back()->with('success', 'Successfully swapped ' . $cxAmount . ' CX for ' . $usdtAmount . ' USDT.');
    }
}
