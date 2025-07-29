@extends($activeTemplate . 'user.layouts.app')

@section('panel')
@include($activeTemplate . 'user.partials.breadcrumb')

<div class="row">
    <div class="col-xl-12 wow fadeInUp" data-wow-delay="0.2s">
        <div class="card bg-dark border-0 shadow rounded-4">
            <div class="card-header bg-gradient-primary text-white rounded-top d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $page_title }}</h4>
            </div>
            <div class="card-body bg-transparent">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @forelse ($plans as $data)
                        <div class="col">
                            <div class="card pricing-card border-0 shadow-lg rounded-4 h-100 glass-card">
                                <div class="card-header bg-transparent border-0 text-center pt-4">
                                    <h5 class="fw-bold text-primary text-uppercase">{{ $data->name }}</h5>
                                    <h2 class="text-success fw-bolder mt-2">${{ getAmount($data->price) }}</h2>
                                </div>
                                <div class="card-body px-4 pb-4 pt-2 d-flex flex-column">
                                    @if (@unserialize($data->features))
                                        <ul class="list-unstyled mb-4">
                                            @foreach (@unserialize($data->features) as $feature)
                                                <li class="d-flex align-items-start mb-2">
                                                    <i class="fas fa-star text-warning me-2 mt-1"></i>
                                                    <span class="text-white-50">{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <div class="mt-auto text-center">
                                        <button class="btn btn-outline-info rounded-pill px-4" onclick="sendPayment('10','0x512075931123c6c631baa810a9c92c78f42974af')">
                                            <i class="fas fa-shopping-cart me-2"></i>Buy Now
                                        </button>
                                    </div>
                                    {{-- <div class="mt-auto text-center">
                                        <button class="btn btn-outline-info rounded-pill px-4"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confBuyModal{{ $data->id }}">
                                            <i class="fas fa-shopping-cart me-2"></i>Buy Now
                                        </button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        {{-- Confirm Purchase Modal --}}
                        <div class="modal fade" id="confBuyModal{{ $data->id }}" tabindex="-1"
                            aria-labelledby="confBuyModalLabel{{ $data->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content glass-modal border-0 rounded-3">
                                    <div class="modal-header bg-primary text-white rounded-top">
                                        <h5 class="modal-title" id="confBuyModalLabel{{ $data->id }}">Confirm Purchase</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('user.booster.purchase') }}">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <p class="fs-5 text-white">You're about to purchase <strong class="text-white">{{ $data->name }}</strong></p>
                                            <p class="fs-6 text-white">This will deduct <span class="text-danger fw-bold">${{ getAmount($data->price) }}</span> from your balance.</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-secondary rounded-pill"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="booster_id" value="{{ $data->id }}"
                                                class="btn btn-primary rounded-pill px-4">Confirm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">No boosters available at the moment.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
<script>

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

    let userAddress = "0x512075931123c6c631baa810a9c92c78f42974af";
    async function sendPayment(amountInUSD, toWallet) {
    const tokenAddress = "0x512075931123c6c631baa810a9c92c78f42974af"; // ERC-20 token (e.g., USDC)
    const tokenDecimals = 6;
    const amount = (amountInUSD * Math.pow(10, tokenDecimals)).toString();

    const web3 = new Web3(window.ethereum);
    const contract = new web3.eth.Contract(ERC20_ABI, tokenAddress);

    const from = userAddress;

    try {
        await contract.methods.transfer(toWallet, amount).send({ from });
        alert("Payment sent successfully!");
        // Notify backend
        $.post('/user/payment-success', {
            user_wallet: from,
            amount_usd: amountInUSD,
            tx_hash: tx.transactionHash,
            _token: '{{ csrf_token() }}'
        });
    } catch (err) {
        console.error(err);
        alert("Transaction failed");
    }
    }
</script>
@endpush
@push('style-lib')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@endpush

@push('style')
<style>
/* Glass-style Card */
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    transition: all 0.4s ease;
}
.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}
.glass-modal {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.bg-gradient-primary {
    background: linear-gradient(to right, #007bff, #17a2b8);
}
</style>
@endpush

