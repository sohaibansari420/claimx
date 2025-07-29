@extends($activeTemplate . 'user.layouts.app')

<style>
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --dark-color: #2d3436;
            --light-color: #f5f6fa;
            --success-color: #00b894;
            --danger-color: #d63031;
            --warning-color: #fdcb6e;
            --info-color: #0984e3;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--dark-color);
        }
        
        .mining-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 5px 20px rgba(108, 92, 231, 0.3);
        }
        
        .mining-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 25px;
            overflow: hidden;
            background-color: white;
        }
        
        .mining-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .mining-card-header {
            background: linear-gradient(135deg, #f5f7fa, #e4e8eb);
            border-bottom: none;
            padding: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .mining-card-body {
            padding: 1.5rem;
        }
        
        .mining-progress {
            height: 10px;
            border-radius: 5px;
            margin: 1rem 0;
        }
        
        .mining-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .mining-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
            color: white;
        }
        
        .mining-btn:disabled {
            background: #ddd;
            transform: none;
            box-shadow: none;
        }
        
        .mining-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .mining-stat {
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }
        
        .mining-stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .mining-stat-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        .mining-timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            text-align: center;
            margin: 1rem 0;
        }
        
        .mining-rewards {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .mining-reward-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .mining-reward-item:last-child {
            border-bottom: none;
        }
        
        .mining-reward-label {
            font-weight: 600;
            color: black;
        }
        
        .mining-reward-value {
            font-weight: 700;
            color: var(--success-color);
        }
        
        .mining-device {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 10px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .mining-device:hover {
            background-color: #e9ecef;
        }
        
        .mining-device-icon {
            font-size: 2rem;
            margin-right: 1rem;
            color: var(--primary-color);
        }
        
        .mining-device-info {
            flex-grow: 1;
        }
        
        .mining-device-name {
            font-weight: 600;
            margin-bottom: 0.2rem;
        }
        
        .mining-device-hashrate {
            font-size: 0.9rem;
            color: #666;
        }
        
        .mining-device-status {
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
        }
        
        .status-active {
            background-color: rgba(0, 184, 148, 0.1);
            color: var(--success-color);
        }
        
        .status-inactive {
            background-color: rgba(214, 48, 49, 0.1);
            color: var(--danger-color);
        }
        
        .mining-referral {
            background: linear-gradient(135deg, #f5f7fa, #e4e8eb);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .mining-referral-code {
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            font-family: monospace;
            font-size: 1.2rem;
            text-align: center;
            margin: 1rem 0;
            word-break: break-all;
            color: black;
        }
        
        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .mining-stats {
                flex-direction: column;
            }
            
            .mining-stat {
                margin-bottom: 1rem;
            }
            
            .mining-header {
                padding: 2rem 0;
            }
            
            .mining-card-body {
                padding: 1rem;
            }
        }
    </style>

@section('panel')

    <header class="mining-header">
        <div class="container text-center">
            <h1><i class="fas fa-digging me-2"></i> Token Mining</h1>
            <p class="lead">Start mining our tokens now and earn rewards automatically</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Mining Control Panel -->
            <div class="col-lg-8">
                <div class="mining-card">
                    <div class="mining-card-header">
                        <i class="fas fa-rocket me-2"></i> Mining Control Panel
                    </div>
                    <div class="mining-card-body">
                        <div class="row mining-stats">
                            <div class="col-md-4">
                                <div class="mining-stat">
                                    <div class="mining-stat-value" id="miningPower">{{ $data['power'] }} %</div>
                                    <div class="mining-stat-label">Mining Power</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mining-stat">
                                    <div class="mining-stat-value" id="hashrate">{{ $data['tap'] }} t/d</div>
                                    <div class="mining-stat-label">Taps/Day</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mining-stat">
                                    <div class="mining-stat-value" id="tokensMined">{{ $data['token_minned'] }} CX</div>
                                    <div class="mining-stat-label">Tokens Mined</div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="mining-timer" id="miningTimer">
                            Ready to start mining
                        </div>
                        
                        <div class="progress mining-progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" id="miningProgress" 
                                 style="width: 0%; background-color: var(--primary-color)"></div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button class="mining-btn" id="startMiningBtn">
                                <i class="fas fa-play me-2"></i> Start Mining
                            </button>
                            {{-- <button class="mining-btn ms-3" id="stopMiningBtn" disabled>
                                <i class="fas fa-stop me-2"></i> Stop Mining
                            </button> --}}
                        </div>
                        
                        <div class="mining-rewards">
                            <h5><i class="fas fa-award me-2"></i>Estimated Rewards </h5>
                            <div class="mining-reward-item">
                                <span class="mining-reward-label">Token Minned:</span>
                                <span class="mining-reward-value" id="hourlyReward">{{ $data['token_minned'] *  ($data['power'] / 100) }} CX</span>
                            </div>
                            <div class="mining-reward-item">
                                <span class="mining-reward-label">Mining Power:</span>
                                <span class="mining-reward-value" id="dailyReward">{{ $data['power'] }} %</span>
                            </div>
                            <div class="mining-reward-item">
                                <span class="mining-reward-label">Days Remaining:</span>
                                <span class="mining-reward-value" id="weeklyReward">{{ $data['stakeDaysRemaining'] }} Days</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mining Devices -->
                <div class="mining-card mt-4">
                    <div class="mining-card-header">
                        <i class="fas fa-chart-line me-2"></i> Mining Statistics
                    </div>
                    <div class="mining-card-body">
                        <div class="mining-reward-item">
                            <span class="mining-reward-label">Total Mined:</span>
                            <span class="mining-reward-value">{{ $totalTokenEarned }} TKN</span>
                        </div>
                        <div class="mining-reward-item">
                            <span class="mining-reward-label">Today's Earnings:</span>
                            <span class="mining-reward-value">{{ $todayEarning->token_earned ?? '0' }} CX</span>
                        </div>
                        <div class="mining-reward-item">
                            <span class="mining-reward-label">Mining Time:</span>
                            <span class="mining-reward-value">{{ $tokenMiningTime }}</span>
                        </div>
                        <div class="mining-reward-item">
                            <span class="mining-reward-label">Referral Earnings:</span>
                            <span class="mining-reward-value">0.00 TKN</span>
                        </div>
                        
                        <div class="text-center mt-3">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mining Stats & Referral -->
            <div class="col-lg-4">
                <!-- Mining Stats -->
                <div class="mining-card">
                    <div class="mining-card-header">
                        <i class="fas fa-chart-line me-2"></i> Token Wallet
                    </div>
                    <div class="mining-card-body">
                        <div class="d-flex align-items-center">
                            <div class="card-box-icon p-3 text-primary">
                                {!! $tokenWallet->wallet->icon !!}
                            </div>
                            <div  class="chart-num">
                                <h2 class="font-w600 mb-0">{{ getAmount($tokenWallet->balance) }} <small>{{ $tokenWallet->wallet->currency }}</small></h2>
                            </div>
                        </div>
                        
                    <div class="text-center">
                        <form method="post" action="{{ route('user.stakeToken') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="font-weight-bold"> @lang('How many tokens you want to stake') :</label>
                                    <input type="number" class="form-control name" name="stake_token" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-block btn btn-outline-primary"><i class="fas fa-share me-2"></i>@lang('Stake Tokens')</button>
                            </div>
                        </form>
                    </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('user.stakingHistory') }}" class="btn btn-outline-info">
                                <i class="fas fa-history me-2"></i> View History
                            </a>
                            {{-- <button class="btn btn-outline-success ms-2">
                                <i class="fas fa-wallet me-2"></i> Withdraw
                            </button> --}}
                        </div>
                    </div>
                </div>
                
                <!-- Referral Program -->
                {{-- <div class="mining-card mt-4">
                    <div class="mining-card-header">
                        <i class="fas fa-user-friends me-2"></i> Referral Program
                    </div>
                    <div class="mining-card-body">
                        <p>Invite friends and earn 10% of their mining rewards!</p>
                        
                        <div class="mining-referral">
                            <h5><i class="fas fa-link me-2"></i>Your Referral Link</h5>
                            <div class="mining-referral-code">
                                https://tokenminer.com/ref/user123
                            </div>
                            <button class="btn btn-primary w-100" id="copyReferralBtn">
                                <i class="fas fa-copy me-2"></i> Copy Link
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
const miningData = {
    tap: {{ $data['tap'] }},
    power: {{ $data['power'] }},
    tokenMinned: {{ $data['token_minned'] }},
    stakeAmount: {{ $data['token_minned'] }},
    startDate: "{{ $startDate }}", // Properly formatted date string
    duration: {{ $sessionDuration }} // in seconds
};

$(document).ready(function () {
    let isMining = false;
    let miningInterval;
    let secondsMined = 0;
    let tokensEarned = 0;
    let currentHashrate = 0;

    const startBtn = $('#startMiningBtn');
    const miningTimer = $('#miningTimer');
    const miningProgress = $('#miningProgress');
    const hashrateDisplay = $('#hashrate');
    const tokensMinedDisplay = $('#tokensMined');
    const miningPowerDisplay = $('#miningPower');
    const hourlyRewardDisplay = $('#hourlyReward');
    const dailyRewardDisplay = $('#dailyReward');
    const weeklyRewardDisplay = $('#weeklyReward');
    const copyReferralBtn = $('#copyReferralBtn');

    const sessionSeconds = miningData.duration;
    const totalReward = miningData.stakeAmount * (miningData.power / 100);
    currentHashrate = 24 / miningData.tap;
    tokensEarned = miningData.tokenMinned;

    function startMiningFrom(secondsElapsed = 0) {
        isMining = true;
        startBtn.prop('disabled', true);
        secondsMined = secondsElapsed;

        miningInterval = setInterval(() => {
            secondsMined++;

            const remaining = sessionSeconds - secondsMined;
            const h = Math.floor(remaining / 3600);
            const m = Math.floor((remaining % 3600) / 60);
            const s = remaining % 60;

            miningTimer.text(
                `Time Left: ${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`
            );

            const progressPercent = (secondsMined / sessionSeconds) * 100;
            miningProgress.css('width', `${progressPercent.toFixed(2)}%`);

            if (secondsMined >= sessionSeconds) {
                clearInterval(miningInterval);
                tokensEarned += totalReward;
                tokensMinedDisplay.text(`${tokensEarned.toFixed(4)} TKN`);
                miningTimer.text("Mining session completed.");
                startBtn.prop('disabled', false);
                isMining = false;
            }
        }, 1000);
    }

    function saveMiningData() {
        $.ajax({
            type: "POST",
            url: "{{ route('user.minningHistory') }}",
            data: {
                data: miningData,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res === 'success') {
                    console.log('Mining session saved.');
                }
            },
            error: function (err) {
                console.error('Error saving mining session:', err);
            }
        });
    }

    startBtn.click(function () {
        if (isMining) return;
        saveMiningData();
        startMiningFrom(0);
    });

    // Resume logic if already started
    if (miningData.startDate && sessionSeconds > 0) {
        const startedAt = new Date(miningData.startDate).getTime();
        const now = Date.now();
        const elapsed = Math.floor((now - startedAt) / 1000);

        if (elapsed < sessionSeconds) {
            startMiningFrom(elapsed);
            startBtn.prop('disabled', true);
        } else {
            startBtn.prop('disabled', false);
            miningTimer.text("Mining session completed.");
        }
    }
});
</script>


@endpush
