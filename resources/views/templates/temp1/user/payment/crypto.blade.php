@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-deposit text-center">
                <div class="card-body card-body-deposit text-center">
                    <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text-success"> {{ number_format($data->amount,2) }}</span>
                        {{ $data->currency }}</h4>
                    <h5 class="mb-2">@lang('TO') <span class="text-success"> {{ $data->sendto }}</span></h5>
                    <img src="{{ $data->img }}" alt="@lang('image')" width="100" height="100">
                    <h4 class="bold my-4">@lang('SCAN TO SEND')</h4>
                    <hr>
                    <h5 class="text-success font-weight-bold">@lang('Your Account will be credited automatically after 3 network confirmations. ')</h5>
                    <h5 class="text-danger font-weight-bold">@lang('Please wait for your amount confirmation. As you amount will soon be recieved in your cash wallet.')</h5>
                    {{-- <h5 class="text-danger font-weight-bold">@lang('Kindly pay $2 extra if you are depositing less then $200 to avoid delays by third party. ')</h5> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg text-center">
                <div class="card-body p-4">
                    <h4 class="mb-4 font-weight-bold">@lang('PLEASE ATTACH SCREENSHOT OF YOUR DEPOSIT')</h4>
                    <form action="{{ route('user.deposit.screenshot')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $deposit->id }}">
    
                        <div class="mb-3">
                            <label for="screenshot" class="form-label font-weight-bold">@lang('Select Image')</label>
                            <input type="file" class="form-control" id="screenshot"
                                   accept=".png, .jpg, .jpeg, .svg, .gif" name="screenshot" required>
                        </div>
    
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">@lang('Add Screenshot')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
