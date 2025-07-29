@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
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
                                    <th scope="col">@lang('id')</th>
                                    <th scope="col">@lang('Token Staked')</th>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stakeTokenHistory as $k=>$history)
                                    <tr>
                                        <td data-label="#@lang('id')">{{ $history->id }}</td>
                                        <td data-label="@lang('Token Staked')">
                                            <strong>{{ getAmount($history->stake_amount) }} CX</strong>
                                        </td>
                                        <td data-label="@lang('Date')">{{ showDateTime($history->start_date) }}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($history->status == 0)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($history->status == 1)
                                                <span class="badge badge--success">@lang('Completed')</span>
                                            @endif
                                        </td>
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
@endsection

@push('script-lib')
    <!-- Datatable -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/datatables.init.js"></script>
@endpush

@push('style-lib')
    <!-- Datatable -->
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
