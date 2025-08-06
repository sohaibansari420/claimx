@extends($activeTemplate . 'user.layouts.app')
@section('panel')

<div class="text-center mt-3 d-none" id="connect-metamask">
    <h2>Connect Your Wallet</h2>
    <button id="connect-wallet" class="btn btn-primary">Connect MetaMask</button>
</div>

<div class="swiper mySwiper-counter py-4">
    <div class="swiper-wrapper row g-4">
        @foreach ($wallets as $wallet)
            @if ($wallet->wallet->display && $wallet->wallet->id != 3)
                <div class="col-md-6">
                    <div class="wallet-card text-white px-4 py-4 rounded-4 shadow"
                        style="background: linear-gradient(180deg, #6c5ce7, #173875); position: relative; overflow: hidden;">

                        {{-- Overlay Bubbles --}}
                        <div style="position:absolute; top:-30px; right:-30px; width:100px; height:100px; background:rgba(255,255,255,0.05); border-radius:50%; z-index:0;"></div>
                        <div style="position:absolute; bottom:-40px; left:-20px; width:120px; height:120px; background:rgba(255,255,255,0.03); border-radius:50%; z-index:0;"></div>

                        {{-- Wallet Icon --}}
                        <div class="mb-3" style="font-size: 2rem; z-index:1; position: relative;">
                            {!! $wallet->wallet->icon !!}
                        </div>

                        {{-- Balance --}}
                        <div class="z-1 position-relative mb-2">
                            <h2 class="fw-bold mb-1" style="font-size: 1.8rem; color:white !important;">
                                {{ getAmount($wallet->balance) }} <span style="font-size: 1rem;">{{ $wallet->wallet->currency }}</span>
                            </h2>
                            <h3 class="mb-0" style="opacity: 0.9; color:white !important;">{{ $wallet->wallet->name }}</h3>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end flex-wrap gap-2 mt-3 z-1 position-relative">
                            <a href="{{ route('user.report.wallet') }}?walletID={{ $wallet->wallet->id }}"
                            class="btn btn-sm"
                            style="background: #344d91; color: white; border-radius: 50px;">
                                Logs
                            </a>

                            @php
                                $isSaturday = \Carbon\Carbon::now()->dayOfWeek === \Carbon\Carbon::SATURDAY;
                                $isWeekend = in_array(\Carbon\Carbon::now()->dayOfWeek, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY]);
                            @endphp

                            {{-- Withdraw or Transfer --}}
                            @if ($userInTree == "1" || $user->withdrawal == 1)
                                @if ($wallet->wallet->withdraw)
                                    @if ($wallet->wallet->id == 3 && $isSaturday)
                                        <a href="{{ route('user.withdraw') }}?walletID={{ $wallet->wallet->id }}"
                                        class="btn btn-sm"
                                        style="background: #344d91; color: white; border-radius: 50px;">
                                        Withdraw
                                        </a>
                                    @elseif ($wallet->wallet->id != 3)
                                        <a href="{{ route('user.withdraw') }}?walletID={{ $wallet->wallet->id }}"
                                        class="btn btn-sm"
                                        style="background: #344d91; color: white; border-radius: 50px;">
                                        Withdraw
                                        </a>
                                    @endif
                                @endif
                            @elseif ($wallet->wallet->withdraw && $user->transfer == 1)
                                @if ($wallet->wallet->id == 3 && $isWeekend)
                                    <a href="{{ route('user.balance.transfer') }}?walletID={{ $wallet->wallet->id }}"
                                    class="btn btn-sm"
                                    style="background: #344d91; color: white; border-radius: 50px;">
                                    {{ $wallet->wallet->id == 1 ? 'Withdraw' : 'Transfer' }}
                                    </a>
                                @elseif ($wallet->wallet->id != 3)
                                    <a href="{{ route('user.balance.transfer') }}?walletID={{ $wallet->wallet->id }}"
                                    class="btn btn-sm"
                                    style="background: #344d91; color: white; border-radius: 50px;">
                                    {{ $wallet->wallet->id == 1 ? 'Withdraw' : 'Transfer' }}
                                    </a>
                                @endif
                            @endif

                            {{-- Deposit --}}
                            @if ($wallet->wallet->deposit)
                                <a href="{{ route('user.deposit') }}"
                                class="btn btn-sm"
                                style="background: #20c997; color: white; border-radius: 50px;">
                                Deposit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
<div class="swiper mySwiper-counter py-4">
    <div class="swiper-wrapper row">
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="mb-3 text-xl font-semibold">Claim X Token Earning History (30 Days)</h5>
            <div style="height: 300px;"> <!-- Fixed height container -->
                <canvas id="claimxChart"></canvas>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-xl-4 wow fadeInUp" data-wow-delay="1.5s">
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img2.jpg);">
                <h3 class="mt-3 mb-1 text-white">Referral Program</h3>
                <p class="text-white mb-0">Invite friends and earn 10% of their mining rewards!</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <h4 class="mb-0">{{ $totalBvCut }} Referrals</h4>
                        <small class="text-dark">Total Referrals Count</small>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <div class="mining-referral">
                            <h5><i class="fas fa-link me-2"></i>Your Referral Link</h5>
                            <div class="mining-referral-code text-dark">
                                http://www.claimxnetwork.com/register?ref=ClaimX
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 mt-0 text-center">		
                <div class="row">
                    <div class="col-12">
                        <input value="{{ route('user.register') }}?ref={{ auth()->user()->username }}" type="hidden" id="left" class="form-control">
                        <button class="btn btn-primary btn-block p-3" onclick="copy('left')">Copy Link</button>
                    </div>	
                </div>						
            </div>
        </div>
    </div>
    <div class="col-xl-8 wow fadeInUp" data-wow-delay="1.6s">
        @php
            // $now = \Carbon\Carbon::now();
            // $created = new \Carbon\Carbon(auth()->user()->check_car);
            // $rem_day = $commissions[1]->commissionDetail[0]->days - $created->diffInDays($now);
            // if($rem_day < 0){
            //     $rem_day = 0;
            // }
        @endphp
        
        <div class="card overflow-hidden">
            <div class="text-center p-3 overlay-box" style="background-image: url({{ asset($activeTemplateTrue) }}/dashboard/images/big/img4.jpg);">
                <h3 class="mt-3 mb-1 text-white">Sponser Bonus</h3>
                <p class="text-white mb-2">Founder's Club Bonus from The Claim X based on higher plan purchase. Get 1% of the Profit from Claim X.</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="mb-0 fs-15">This bonus is a special incentive program offered by our Multi-level-Marketing company to reward the pioneering members who have contributed significantly to the growth and success of our buisness. As part of this program, eligible founders are entitled to receive a 1% share of the company's total profits.</p>
                    </div>
                    {{-- <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">${{ @$direct_sale }}</h4>
                            <small>Current Direct Sale</small>
                        </div>
                    </div> --}}
                    {{-- <div class="col-6 mt-4">
                        <div class="bgl-primary rounded p-3">
                            <h4 class="mb-0">{{ $rem_day }}</h4>
                            <small>Remaining Days</small>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="swiper mySwiper-counter py-4">
    <div class="swiper-wrapper row">
        <div class="">
            <div class="wow fadeInUp" data-wow-delay="1.2s">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title text-white">User Staking History</div>
                        <div class="card-options ">
                            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                    class="fe fe-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered w-100 text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">@lang('Date')</th>
                                        <th scope="col">@lang('Username')</th>
                                        <th scope="col">@lang('Detail')</th>
                                        <th scope="col">@lang('TRX')</th>
                                        <th scope="col">@lang('Amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $trans)  
                                        <tr>
                                            <td class="text-muted" >{{ $trans->created_at }}</td>
                                            <td class="text-muted" >{{ $trans->user->username }}</td>
                                            <td class="text-muted" >{{ $trans->details }}</td>
                                            <td class="text-muted" >{{ $trans->trx }}</td>
                                            <td class="text-muted" >${{ number_format($trans->amount,2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- table-wrapper -->
                </div>
                <!-- section-wrapper -->

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <script>
        let userAddress = "";
        async function isMetaMaskConnected() {
            if (typeof window.ethereum === 'undefined') {
                console.log("MetaMask is not installed");
                $("#connect-metamask").removeClass('d-none'); // Show "Connect" button
                return false;
            }

            try {
                const accounts = await window.ethereum.request({ method: 'eth_accounts' });

                if (accounts.length > 0) {
                    console.log("Connected MetaMask account:", accounts[0]);
                    $("#connect-metamask").addClass('d-none'); // Hide "Connect" button
                    return true;
                } else {
                    console.log("MetaMask is installed but not connected");
                    $("#connect-metamask").removeClass('d-none'); // Show "Connect" button
                    return false;
                }
            } catch (error) {
                console.error("Error checking MetaMask connection:", error);
                $("#connect-metamask").removeClass('d-none');
                return false;
            }
        }
        isMetaMaskConnected();
        document.getElementById("connect-wallet").onclick = async () => {
            if (typeof window.ethereum !== 'undefined') {
            try {
                const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
                userAddress = accounts[0];
                console.log("Connected wallet:", userAddress);
                // Send to backend if needed
            } catch (err) {
                alert("User denied wallet connection");
            }
            } else {
            alert("Please install MetaMask");
            }
        };
    </script>
    <script>
        var copy = function(elementId) {

            var input = document.getElementById(elementId);
            var isiOSDevice = navigator.userAgent.match(/ipad|iphone/i);
            var tempInput = document.createElement("input");
            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
            tempInput.value = input.value;
            tempInput.readOnly = true;
            document.body.appendChild(tempInput);
            if (isiOSDevice) {

                var editable = tempInput.contentEditable;
                var readOnly = tempInput.readOnly;

                tempInput.contentEditable = true;
                tempInput.readOnly = false;

                var range = document.createRange();
                range.selectNodeContents(tempInput);

                var selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);

                tempInput.setSelectionRange(0, 999999);
                tempInput.contentEditable = editable;
                tempInput.readOnly = readOnly;

            } else {
                tempInput.select();
            }
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            notify('success', 'Copied successfully');
        };
    </script>
    <script>
        const claimxLabels = {!! json_encode($tokenHistory->pluck('date')) !!};
        const claimxData = {!! json_encode($tokenHistory->pluck('earned')) !!};

        const ctx = document.getElementById('claimxChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(90, 103, 216, 0.4)');
        gradient.addColorStop(1, 'rgba(90, 103, 216, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: claimxLabels,
                datasets: [{
                    label: 'Claim X Tokens',
                    data: claimxData,
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#5A67D8',
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.parsed.y} Claim X`
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date',
                            font: { size: 12 }
                        },
                        ticks: {
                            maxTicksLimit: 10
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 1,
                        title: {
                            display: true,
                            text: 'Tokens Earned',
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    </script>
@endpush
@push('script-lib')
    <!-- Datatable -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/datatables.init.js"></script>
@endpush

@push('style-lib')
    <!-- Datatable -->
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
