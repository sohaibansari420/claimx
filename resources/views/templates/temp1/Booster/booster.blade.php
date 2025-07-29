@extends($activeTemplate . 'user.layouts.app')

@section('panel')
@include($activeTemplate . 'user.partials.breadcrumb')

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-dark text-white rounded-top d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Air Token Boosters</h5>
                <i class="fas fa-bolt fa-lg text-warning"></i>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    {{-- Tap Booster --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-gradient bg-primary">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="text-uppercase opacity-75">Tap Booster</h6>
                                        <h2 class="fw-bold display-5">{{ $boosters['tap'] ?? 'N/A' }}</h2>
                                    </div>
                                    <i class="fas fa-hand-pointer fa-3x opacity-50"></i>
                                </div>
                                <a href="{{ route('user.plan.details', ['title' => 'tap']) }}"
                                   class="btn btn-outline-light rounded-pill w-100 mt-auto">
                                    View Tap Plans
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Speed Booster --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-gradient bg-success">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="text-uppercase opacity-75">Speed Booster</h6>
                                        <h2 class="fw-bold display-5">{{ $boosters['speed'] ?? 'N/A' }}</h2>
                                    </div>
                                    <i class="fas fa-tachometer-alt fa-3x opacity-50"></i>
                                </div>
                                <a href="{{ route('user.plan.details', ['title' => 'speed']) }}"
                                   class="btn btn-outline-light rounded-pill w-100 mt-auto">
                                    View Speed Plans
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- card-body --}}
        </div>
    </div>
</div>
@endsection

@push('style-lib')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush
