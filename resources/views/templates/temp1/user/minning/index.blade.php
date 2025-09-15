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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-lg-8">
                <ul class="nav nav-pills justify-content-center mb-4" id="miningTabs" role="tablist" style="gap: 10px;">
                    @foreach ($stakeArr as $index => $stake)
                        <li class="nav-item" role="presentation" style="flex: 1;">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }} w-100 text-white fw-bold" 
                                id="tab_{{$stake['id']}}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#panel-{{$stake['id']}}" 
                                type="button" 
                                role="tab" 
                                aria-controls="panel-{{$stake['id']}}" 
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                style="background-color: #007bff; border-radius: 8px;">
                                @if ($stake['package'] == "Free")
                                    🚀 Free Mining
                                    @else
                                    💎 ${{ $stake['token'] }} Mining
                                @endif    
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="miningTabsContent">
                    @foreach ($stakeArr as $index => $stake)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="panel-{{$stake['id']}}" role="tabpanel" aria-labelledby="tab_{{$stake['id']}}"
                            data-duration="{{ $stake['duration'] }}"
                            data-stake-amount="{{ $stake['token'] }}"
                            data-power="{{ $stake['power'] }}"
                            data-token-minned="{{ $stake['token'] }}"
                            data-start-date="{{ $stake['start_date'] }}"
                            data-tap="{{ $stake['tap'] }}"
                            data-booster-purchase-id="{{ $stake['booster_purchase_id'] }}">
                            <div class="col-lg-12">
                                <div class="mining-card">
                                    <div class="mining-card-header">
                                        <i class="fas fa-rocket me-2"></i> Mining Control Panel
                                    </div>
                                    <div class="mining-card-body">
                                        <div class="row mining-stats">
                                            <div class="col-md-4">
                                                <div class="mining-stat">
                                                    <div class="mining-stat-value" id="miningPower_free">{{ $stake['power'] }} %</div>
                                                    <div class="mining-stat-label">Mining Power</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mining-stat">
                                                    <div class="mining-stat-value" id="hashrate_free">{{ $stake['tap'] }} t/d</div>
                                                    <div class="mining-stat-label">Taps/Day</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mining-stat">
                                                    <div class="mining-stat-value" id="tokensMined_free">{{ $stake['token'] }} CX</div>
                                                    <div class="mining-stat-label">Tokens Mined</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mining-timer_{{$stake['id']}} mining-timer" id="miningTimer_{{$stake['id']}}">
                                            Ready to start mining
                                        </div>

                                        <div class="progress mining-progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                role="progressbar" id="miningProgress_{{$stake['id']}}" 
                                                style="width: 0%; background-color: var(--primary-color)"></div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button class="mining-btn" id="startMiningBtn_{{ $stake['id'] }}">
                                                <i class="fas fa-play me-2"></i> Start Mining
                                            </button>
                                        </div>

                                        <div class="mining-rewards">
                                            <h5><i class="fas fa-award me-2"></i>Estimated Rewards </h5>
                                            <div class="mining-reward-item">
                                                <span class="mining-reward-label">Token Minned:</span>
                                                <span class="mining-reward-value" id="hourlyReward_free">{{ $stake['token'] *  ($stake['power'] / 100) }} CX</span>
                                            </div>
                                            <div class="mining-reward-item">
                                                <span class="mining-reward-label">Mining Power:</span>
                                                <span class="mining-reward-value" id="dailyReward_free">{{ $stake['power'] }} %</span>
                                            </div>
                                            <div class="mining-reward-item">
                                                <span class="mining-reward-label">Days Remaining:</span>
                                                <span class="mining-reward-value" id="weeklyReward_free">{{ $stake['days_remaining'] }} Days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mining-card mt-4">
                                    <div class="mining-card-header">
                                        <i class="fas fa-chart-line me-2"></i> Mining Statistics
                                    </div>
                                    <div class="mining-card-body">
                                        <div class="mining-reward-item">
                                            <span class="mining-reward-label">Total Mined:</span>
                                            <span class="mining-reward-value">{{ $totalTokenEarned ?? '' }} CX</span>
                                        </div>
                                        <div class="mining-reward-item">
                                            <span class="mining-reward-label">Today's Earnings:</span>
                                            <span class="mining-reward-value">{{ $todayEarning ?? '0' }} CX</span>
                                        </div>
                                        <div class="mining-reward-item">
                                            <span class="mining-reward-label">Mining Time:</span>
                                            <span class="mining-reward-value">{{ $tokenMiningTime ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                            <div  class="chart-num wallet_balance" id="wallet_balance">
                                <h2 class="font-w600 mb-0">{{ getAmount($tokenWallet->balance) }} <small>{{ $tokenWallet->wallet->currency }}</small></h2>
                            </div>
                        </div>
                        <div class="text-center">
                            <form method="post" action="{{ route('user.stakeToken') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="booster_id" class="col-form-label">Select Booster To Stake:</label>
                                        <select name="booster_id" class="form-control" id="booster_id">
                                            <option value="">Select Booster package to Stake!!!</option>
                                            @foreach ($purchaseBooster as $booster)
                                                <option value="{{ $booster->id }}">{{ $booster->booster->name  }}| {{ $booster->amount }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-block btn btn-outline-primary"><i class="fas fa-share me-2"></i>@lang('Stake Tokens')</button>
                                </div>
                            </form>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('user.stakingHistory') }}" class="btn btn-success">
                                <i class="fas fa-history me-2"></i> View History
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Token Swap Panel -->
                <div class="minning-card shadow-sm border-0 mt-4">
                    <div class="card-header bg-dark text-white d-flex align-items-center">
                        <i class="fas fa-exchange-alt me-2"></i>
                        <strong>Swap Token to USDT</strong>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('user.swapToken') }}" id="swapForm">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="number" step="10" min="10" max="1000" name="amount" class="form-control" id="swapAmount" placeholder="Enter amount" required>
                                <label for="swapAmount">Amount to Swap (CX)</label>
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input type="text" id="usdtEstimate" class="form-control bg-light" placeholder="USDT estimate" readonly>
                                <label for="usdtEstimate">You will receive (estimated USDT)</label>
                                <div id="loadingEstimate" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 translate-middle-y me-3 d-none" role="status"></div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 d-flex justify-content-center align-items-center">
                                <i class="fas fa-sync-alt me-2"></i> Swap Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-lib')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
  // Store intervals by tabId
  const miningIntervals = {};

  // For each tab (panel)
  $('.tab-pane').each(function () {
    const panel = $(this);
    const tabId = panel.attr('id').replace('panel-', '');

    const startBtn = $('#startMiningBtn_' + tabId);
    const miningTimer = $('#miningTimer_' + tabId);
    const miningProgress = $('#miningProgress_' + tabId);
    const tokensMinedDisplay = $('#tokensMined_' + tabId);

    // Read data attributes
    const duration = parseInt(panel.data('duration')); // total session seconds
    const stakeAmount = parseFloat(panel.data('stake-amount'));
    const power = parseFloat(panel.data('power'));
    const tokenMinned = parseFloat(panel.data('token-minned'));
    const startDateStr = panel.data('start-date');
    const taps = parseFloat(panel.data('tap'));
    const boosterPurchaseID = parseFloat(panel.data('booster-purchase-id'));

    let tokensEarned = 0;
    let isMining = false;
    let secondsMined = 0;

    // Calculate total reward for session
    const totalReward = stakeAmount * (power / 100);

    // Function to update UI timer & progress bar
    function updateTimerUI(remainingSeconds) {
      if (remainingSeconds < 0) remainingSeconds = 0;
      const h = Math.floor(remainingSeconds / 3600);
      const m = Math.floor((remainingSeconds % 3600) / 60);
      const s = remainingSeconds % 60;

      miningTimer.text(
        `Time Left: ${h.toString().padStart(2, '0')}:` +
        `${m.toString().padStart(2, '0')}:` +
        `${s.toString().padStart(2, '0')}`
      );

      const progressPercent = ((duration - remainingSeconds) / duration) * 100;
      miningProgress.css('width', progressPercent.toFixed(2) + '%');
    }

    // Start mining timer from elapsed seconds
    function startMiningFrom(elapsedSeconds) {
      if (isMining) return;

      isMining = true;
      secondsMined = elapsedSeconds;
      tokensEarned = (totalReward * (elapsedSeconds / duration));

      startBtn.prop('disabled', true);

      updateTimerUI(duration - secondsMined);
      tokensMinedDisplay.text(tokensEarned.toFixed(4) + ' CX');

      miningIntervals[tabId] = setInterval(() => {
        secondsMined++;
        if (secondsMined >= duration) {
          clearInterval(miningIntervals[tabId]);
          miningTimer.text('Mining session completed.');
          miningProgress.css('width', '100%');
          tokensMinedDisplay.text(totalReward.toFixed(4) + ' CX');
          startBtn.prop('disabled', false);
          isMining = false;
          return;
        }

        const remaining = duration - secondsMined;
        tokensEarned = totalReward * (secondsMined / duration);

        updateTimerUI(remaining);
        tokensMinedDisplay.text(tokensEarned.toFixed(4) + ' CX');
      }, 1000);
    }

    // Save mining start session to server
    function saveMiningStart() {
      const payload = {
        tabId: tabId,
        package: panel.data('package') || '', // if you send this from backend
        tokenMinned: tokenMinned,
        power: power,
        taps: taps,
        startDate: new Date().toISOString(),
        boosterPurchaseID : boosterPurchaseID,
      };

      return $.ajax({
        type: 'POST',
        url: '{{ route("user.minningHistory") }}',
        data: JSON.stringify(payload),
        contentType: 'application/json',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        success: function (response) {
            if (response.status == "success") {
                let $wallet = $('#wallet_balance');
                let newBalance = parseFloat(response.balance) || 0;
                let html_token_wallet = `<h2 class="font-w600 mb-0">${ newBalance.toFixed(2) } <small>${response.currency}</small></h2>`;
                $wallet.html(html_token_wallet); // keep 2 decimals
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
      });
    }

    // On click of start button
    startBtn.off('click').on('click', function () {
      if (isMining) return;

      saveMiningStart()
        .done(function (res) {
          if (res.status === 'success' || res.status === 'free') {
            startMiningFrom(0);
          } else {
            alert('Failed to start mining. Please try again.');
          }
        })
        .fail(function () {
          alert('Error starting mining. Please try again.');
        });
    });

    // On page load, check if mining was started previously (startDateStr)
    if (startDateStr) {
      const startedAt = new Date(startDateStr).getTime();
      const now = Date.now();
      const elapsedSeconds = Math.floor((now - startedAt) / 1000);

      if (elapsedSeconds < duration) {
        startMiningFrom(elapsedSeconds);
      } else {
        miningTimer.text('Mining session completed.');
        miningProgress.css('width', '100%');
        tokensMinedDisplay.text(totalReward.toFixed(4) + ' CX');
        startBtn.prop('disabled', false);
      }
    } else {
      miningTimer.text('Ready to start mining');
      miningProgress.css('width', '0%');
      startBtn.prop('disabled', false);
    }
  });
});

    </script>
    <script>
        function updateSwapEstimate(amountInputId, estimateInputId, rate = 1) {
            const amountInput = document.getElementById(amountInputId);
            const estimateInput = document.getElementById(estimateInputId);

            amountInput.addEventListener("input", function () {
                let amount = parseFloat(amountInput.value);
                if (!amount || amount <= 0) {
                    estimateInput.value = "";
                    return;
                }

                let estimate = amount * rate;
                estimateInput.value = estimate.toFixed(2) + " USDT";
            });
        }

        // Call the function when the page is ready
        document.addEventListener("DOMContentLoaded", function () {
            updateSwapEstimate("swapAmount", "usdtEstimate", 1); // 1 CX = 1 USDT
        });
    </script>

@endpush