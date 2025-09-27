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
        $token_stakes = StakeToken::where('status',"1")->where("user_id",Auth::id())->get();
        $miningHistory = MinningHistory::where('user_id', Auth::id());

        $purchaseBooster = PurchasedBooster::where('is_expired',"1")->where("user_id",Auth::id())->get();
        
        $stakeArr = [];

        foreach ($token_stakes as $key => $stake) {
            $isBoosterPurchase = PurchasedBooster::with("booster")->where("user_id",Auth::id())->where('amount',$stake->stake_amount)->where("id",$stake->booster_purchase_id)->first();
            
            $tap   = 0;
            $power = 0;
            $days  = 0 ;
            if(isset($isBoosterPurchase)){
                $tap = 0;
                $power = 0;
                    $booster_Check = $isBoosterPurchase->booster;
                    $features = unserialize($booster_Check->features);
                    $features =  unserialize($features[0]);
                    $tap = "1";
                    preg_match('/([\d\.]+)%/', $features[0], $matchPower);
                    preg_match('/\d+/', $features[2], $matchDays);
                    $power = isset($matchPower[1]) ? floatval($matchPower[1]) : 0.0;
                    $days = isset($matchDays[0]) ? floatval($matchDays[0]) : 0.0;

                    $isPromotional = $isBoosterPurchase->x_term;
                    if ($isPromotional > 1) {
                        $days = $days * $isPromotional;
                    }
                    if (isset($stake->start_date)) {
                        $startDate = Carbon::parse($stake->start_date);
                        $stakeDaysPassed = $startDate->diffInDays(Carbon::now());
                    }
                    else{
                        $stakeDaysPassed = "0";
                    }
                    $stakeDaysRemaining = $days - $stakeDaysPassed ;

                    $miningRecord = MinningHistory::where('user_id', Auth::id())
                    ->where('token_mined', $stake->stake_amount)
                    ->where('booster_purchase_id', $stake->booster_purchase_id)
                    ->latest()
                    ->first();
                    
                    $tap = $tap ?? "1"; // fallback
                    $duration = (24 / $tap) * 3600;

                    $obj = [];
                    $obj['id'] = $stake->id; 
                    $obj['tap'] = $tap;
                    $obj['power'] = $power;
                    $obj['days'] = $days;
                    $obj['package'] = $booster_Check->name;
                    $obj['token'] = $stake->stake_amount;
                    $obj['days_remaining'] = $stakeDaysRemaining;
                    $obj['start_date'] = optional($miningRecord)->start_date; // may be null if never started
                    $obj['duration'] = $duration;
                    $obj['booster_purchase_id'] = $stake->booster_purchase_id;

                    array_push($stakeArr , $obj);
                    if($stakeDaysRemaining == 0){
                        $user = Auth::user();
                        $wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', "3")->firstOrFail(); // adjust based on your model relationship
                        
                        $isPromotional = $isBoosterPurchase->is_promotional;
                        if ($isPromotional == 1) {
                            $cxAmount = $miningHistory->where('booster_purchase_id',$stake->booster_purchase_id)->where('token_mined',$stake->stake_amount)->sum('token_earned');
                        }
                        else {
                            $cxAmount = $stake->stake_amount;
                        }

                        $trx = getTrx();
                        
                        $details = 'Token minning id completed...';
                        updateWallet($user->id, $trx, '3', NULL, '+', getAmount($cxAmount), $details , 0, 'minning_completed', NULL,'');

                        StakeToken::where('status',"1")->where("user_id",Auth::id())->update(['status'=>'0']);
                    }
            }
            else{
                $tap   = "1";
                $power = "1";
                $days  = "300" ;

                if (isset($stake->start_date)) {
                    $startDate = Carbon::parse($stake->start_date);
                    $stakeDaysPassed = $startDate->diffInDays(Carbon::now());
                }
                else{
                    $stakeDaysPassed = "0";
                }
                $stakeDaysRemaining = $days - $stakeDaysPassed ;

                $miningRecord = MinningHistory::where('user_id', Auth::id())
                    ->where('token_mined', $stake->stake_amount)
                    ->where('booster_purchase_id', $stake->booster_purchase_id)
                    ->latest()
                    ->first();

                $tap = $tap ?? 1; // fallback
                $duration = (24 / $tap) * 3600; 

                $obj = [];
                $obj['id'] = $stake->id;
                $obj['tap'] = $tap;
                $obj['power'] = $power;
                $obj['days'] = $days;
                $obj['package'] = "Free";
                $obj['token'] = $stake->stake_amount;
                $obj['days_remaining'] = $stakeDaysRemaining;
                $obj['start_date'] = optional($miningRecord)->start_date; // may be null if never started
                $obj['duration'] = $duration;
                $obj['booster_purchase_id'] = $stake->booster_purchase_id;

                array_push($stakeArr , $obj);

                if($stakeDaysRemaining == 0){
                    $user = Auth::user();
                    $wallet = UserWallet::where('user_id', $user->id)->where('wallet_id', "3")->firstOrFail(); // adjust based on your model relationship
                    
                    // $cxAmount = $miningHistory->where('token_mined',$stake->stake_amount)->sum('token_earned');
                    $cxAmount = $stake->stake_amount;
                    $trx = getTrx();
                    
                    $details = 'Token minning id completed...';
                    updateWallet($user->id, $trx, '3', NULL, '+', getAmount($cxAmount), $details , 0, 'minning_completed', NULL,'');

                    StakeToken::where('status',"1")->where("user_id",Auth::id())->update(['status'=>'0']);
                }
                
            }
            
        }
        // Minning Statistics
        
        $histories = $miningHistory->get();
        
        $mining = $miningHistory->latest()->first();
        

        $startDate = optional($mining)->start_date;
        $miningTaps = $mining->taps ?? '1';
        $sessionDuration = (24/$miningTaps) * 3600; 

        $totalTokenEarned = $miningHistory->sum('token_earned');
        $todayEarning = $miningHistory->where('start_date',Carbon::today())->sum('token_earned');

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
        // End Minning Statistics
        return view($this->activeTemplate . 'user.minning.index', compact('page_title',
            'empty_message',
            'tokenWallet',
            'startDate',
            'sessionDuration',
            'mining',
            
            'totalTokenEarned',
            'todayEarning',
            'tokenMiningTime',

            'stakeArr',
            'purchaseBooster'
        ));
    }

    public function stakeToken(Request $request){
        
        $this->validate($request, [
            'booster_id'  => 'required',
        ]);

        $booster = PurchasedBooster::where('id',$request->booster_id)->first();
        if($booster->amount <= 0){
            $notify[] = ['error', 'Insufficient Token to stake'];
            return back()->withNotify($notify);
        }
        $notify[] = ['success','CX token is on stake'];
        $details = 'CX token is on stake';
        $user_id = Auth::id();
        $trx = getTrx();

        $user_wallet = UserWallet::where('user_id', $user_id)->where('wallet_id', "3")->firstOrFail();

        $amount = $booster->amount;

        $data = [
            "user_id"=> $user_id,
            "booster_purchase_id" => $booster->id,
            "stake_amount"=> $amount,
            "start_date" => Carbon::now(),
            "status" =>  "1",
        ];
        
        updateWallet($user_id, $trx, $user_wallet->wallet_id, NULL, '-', getAmount($amount), $details , 0, 'user_staking', NULL,'');

        StakeToken::create($data);

        $booster->is_expired = '0';
        $booster->update();

        return redirect()->route('user.minning')->withNotify($notify);
    }

    public function tokenReport(){
        
        $page_title = 'User Report';
        $stakeTokenHistory = StakeToken::where('user_id', Auth::id())->paginate(getPaginate());

        $empty_message = 'No transactions.';
        return view($this->activeTemplate .'Report.index', compact('page_title', 'stakeTokenHistory', 'empty_message'));
    }

    public function minningHistory(Request $request){
        $tokenEarned = $request->tokenMinned * ($request->power / 100);
        $user_id = Auth::id();
        $data = [
            "user_id"  =>$user_id ,
            "token_mined"  =>$request->tokenMinned ,
            "power"  =>$request->power ,
            "taps"  =>$request->taps ,
            "start_date"  =>Carbon::now() ,
            "token_earned"  =>$tokenEarned ,
            "booster_purchase_id" => $request->boosterPurchaseID,
        ];
        MinningHistory::create($data);
        $isPromo = PurchasedBooster::where('id', $request->boosterPurchaseID)->where('user_id',$user_id)->first();
        if ($request->boosterPurchaseID !== 0 && $isPromo->is_promotional == 0) {
            $cxAmount = $tokenEarned;
            $trx = getTrx();
            $details = 'Token roi amount...';
            updateWallet($user_id, $trx, '3', NULL, '+', getAmount($cxAmount), $details , 0, 'token_roi', NULL,'');
        }     
        else{
            return response()->json([
                'status' => 'free',
            ]);
        }       
        $wallet = UserWallet::where('user_id',$user_id)->where('wallet_id','3')->first();
        $notify[] = ['success','Minning Started'];
        return response()->json([
            'status'  => 'success',
            'balance' => getAmount($wallet->balance),
            'currency'=> $wallet->wallet->currency,
        ]);
    }

    public function stakingHistory(){
        $page_title = 'Staking History';
        $stakeTokenHistory = StakeToken::where('user_id', Auth::id())->paginate(getPaginate());

        return view($this->activeTemplate .'user.minning.token_stake_history', compact('page_title', 'stakeTokenHistory'));
    }

    public function swapToken(Request $request){
        $request->validate([
            'amount' => 'required|numeric|min:10',
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
        updateWallet($user->id, $trx, '1', NULL, '+', getAmount($cxAmount), $details , 0, 'swapped_token', NULL,'');
        
        return back()->with('success', 'Successfully swapped ' . $cxAmount . ' CX for ' . $usdtAmount . ' USDT.');
    }

    public function freeStakeToken(Request $request){
        $notify = ['success','Congratulation... You 100CX token is on stake....'];
        $amount = "100";
        $user = Auth::user();
        $data = [
            "user_id"=> $user->id,
            "booster_purchase_id" => "0",
            "stake_amount"=> $amount,
            "start_date" => Carbon::now(),
            "status" =>  "1",
        ];

        StakeToken::create($data);

        return redirect()->route('user.minning')->withNotify($notify);
    }
}
