@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.deposit.list.transfer'))
            <div class="col-md-4 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 b-radius--5 bg--success">
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ number_format($transferSend,2) }}
                        </h2>
                        <p class="text-white">@lang('Successful Transfer')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
            <div class="col-md-4 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 b-radius--5 bg--6">
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ number_format($transfersReceive,2) }}
                        </h2>
                        <p class="text-white">@lang('Pending Receive')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
        @endif
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Date')</th>
                                    @if (!request()->routeIs('admin.users.deposits') && !request()->routeIs('admin.users.deposits.method'))
                                        <th scope="col">@lang('Username')</th>
                                    @endif
                                    
                                    <th scope="col">@lang('Detail')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $transfer)
                                    <tr>
                                        <td data-label="@lang('Date')"> {{ showDateTime($transfer->created_at) }}</td>
                                        @if (!request()->routeIs('admin.users.deposits') && !request()->routeIs('admin.users.deposits.method'))
                                            <td data-label="@lang('Username')"><a
                                                    href="{{ route('admin.users.detail', $transfer->user_id) }}">{{ $transfer->user->username }}</a>
                                            </td>
                                        @endif
                                        <td data-label="@lang('Action')"> {{ $transfer->details }}</td>
                                        <td data-label="@lang('Amount')" class="font-weight-bold"> {{ getAmount($transfer->amount) }} {{ __($general->cur_text) }}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($transfer->remark == "user_transfer_balance")
                                                <span class="badge badge--success">@lang('Transfer')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Receive')</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($transfers) }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            if (!$('.datepicker-here').val()) {
                $('.datepicker-here').datepicker();
            }
        })(jQuery)
    </script>
@endpush
