@extends($activeTemplate . 'user.layouts.app')
@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">Withdraw</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <!--Row-->
                    <div class="row mt-4 justify-content-center">
                        <div class="col-12 text-center">
                        </div>
                            @foreach ($withdrawMethod as $data)
                                <div class="col-lg-4 col-sm-12 p-l-0 p-r-0">
                                    <div class="card text-center">
                                        <div class="widget-line mt-4">
                                            <h2>Withdraw via {{ __($data->name) }}</h2>
                                        </div>
                                        <div class="mx-auto chart-circle chart-circle-md mt-2" data-value="0.55"
                                            data-thickness="20" data-color="#38a01e">
                                            <div class="chart-circle-value fs">
                                                <img src="{{ getImage(imagePath()['withdraw']['method']['path'] . '/' . $data->image) }}"
                                                    class="box-img-top depo" alt="{{ __($data->name) }}">
                                            </div>
                                        </div>
                                        <ul class="list-group text-center font-15 mt-4">
                                            <li class="list-group-item ">@lang('Limit')
                                                : {{ getAmount($data->min_limit) }}
                                                - {{ getAmount($data->max_limit) }} {{ $general->cur_text }}</li>
                                            <li class="list-group-item "> @lang('Charges')
                                                - 10%
                                            </li>
                                            <li class="list-group-item">@lang('Processing Time')
                                                - {{ $data->delay }}</li>
                                        </ul>
                                        <div class="row justify-content-center mt-5 mb-5">
                                            <div class="col-11">
                                                @if (@$created_at && $remaining > 0)
                                                    <h4 id="note"></h4>
                                                @else
                                                    <div class="form-group">
                                                        <label>@lang('Enter Amount'):</label>
                                                        <div class="input-group p-2">
                                                            <input id="amount" type="text" class="form-control form-control-lg"
                                                                onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                                name="amount" placeholder="0.00" required value="{{ old('amount') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mt-auto text-center">
                                                        <button class="btn btn-outline-info rounded-pill px-4" id="withdrawBtn">
                                                            <i class="fas fa-shopping-cart me-2"></i>Withdraw Now
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                    <!--End row-->  
                </div>
            </div>
            <!-- section-wrapper -->
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    {{-- <script>
        const ERC20_ABI = [
            {
            constant: false,
            name: "transfer",
            type: "function",
            inputs: [
                { name: "_to", type: "address" },
                { name: "_value", type: "uint256" }
            ],
            outputs: [{ name: "", type: "bool" }],
            }
        ];
        // const receiverWallet = "0x8dadfd41dA68d59B6ff6d50F282183235B6C367b";
        const receiverWallet = "0x13e4Bb44bB78023ef55f6fCB887E8592C5CCddEe";
        async function ReceivePayment() {
            const tokenAddress = "0x55d398326f99059fF775485246999027B3197955"; // USDT (BSC)
            const tokenDecimals = 18;

            const amountInUSD = $("#amount").val();
            if (!amountInUSD) {
                alert("Enter the Withdraw amount to proceed!");
                return;
            }

            if (!window.ethereum) {
                alert("Please install MetaMask");
                return;
            }

            const web3 = new Web3(window.ethereum);
            await window.ethereum.request({ method: "eth_requestAccounts" });

            const accounts = await web3.eth.getAccounts();
            const from = accounts[0];

            if (!receiverWallet  || !Web3.utils.isAddress(receiverWallet )) {
                alert("Recipient wallet address is invalid.");
                return;
            }

            // Ensure on Binance Smart Chain
            // const chainId = await web3.eth.getChainId();
            // if (chainId !== 56) { // 56 = BSC mainnet
            //     alert("Please switch MetaMask to Binance Smart Chain.");
            //     return;
            // }

            // Convert amount to smallest unit (BN)
            // const amount = web3.utils.toBN(web3.utils.toWei(amountInUSD, "ether"))
            // .div(web3.utils.toBN(10).pow(web3.utils.toBN(18 - tokenDecimals)));
            const amount = (amountInUSD * Math.pow(10, tokenDecimals)).toString();
            const contract = new web3.eth.Contract(ERC20_ABI, tokenAddress);

            try {
                const tx = await contract.methods.transfer(receiverWallet, amount).send({ from });
                alert("Withdrawal sent successfully ✅");
                console.log("Tx Hash:", tx.transactionHash);
            } catch (error) {
                console.error("Transaction failed ❌", error);
                alert("Transaction failed ❌");
            }
        }
    </script> --}}
  <script>
    const walletID = {{ $wallet_id }}
$('#withdrawBtn').click(async function() {
    var $btn = $(this);
    var amount = $('#amount').val();

    if (!amount || amount < 20) {
        alert('Please enter wallet and amount');
        return;
    }

    // Check for MetaMask / Ethereum provider
    if (!window.ethereum) {
        alert('Token Pocket or compatible wallet not detected!');
        return;
    }

    if ($btn.prop('disabled')) return;
    $btn.prop('disabled', true).text('Processing...');  

    try {
        // Request account access
        const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
        const userAccount = accounts[0];

        // Send AJAX request with amount and user wallet
        $.ajax({
            url: "{{ route('user.withdraw.WalletWEBWithdrawal') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                amount: amount,
                walletID : walletID,
                wallet: userAccount
            },
            success: function(res) {
                alert(res.success || res.error || 'Transaction sent');
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'Transaction failed');
            }
        });
    } catch (err) {
        console.error(err);
        alert('User denied wallet connection');
    }
    finally {
        // Re-enable button
        $btn.prop('disabled', false).text('Withdraw');
    }
});
</script>

@endpush

