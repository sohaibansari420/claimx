@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Staked Tokens Over Time</strong>
                </div>
                <div class="card-body">
                    <canvas id="stakeChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border shadow-sm">
                <div class="card-header bg-success text-white">
                    <strong>Stake Status Overview</strong>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    </div>
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
    @php
        $dates = [];
        $amounts = [];
        $statusCount = ['pending' => 0, 'completed' => 0];

        foreach ($stakeTokenHistory as $history) {
            $dates[] = showDateTime($history->start_date, 'Y-m-d');
            $amounts[] = getAmount($history->stake_amount);

            if ($history->status == 0) $statusCount['pending']++;
            elseif ($history->status == 1) $statusCount['completed']++;
        }
    @endphp
@endsection
@push('script')
<script>
    const stakeDates = @json($dates);
const stakeAmounts = @json($amounts);
const statusCount = @json($statusCount);

// Line chart for Token Staked
const ctx1 = document.getElementById('stakeChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: stakeDates,
        datasets: [{
            label: 'Tokens Staked',
            data: stakeAmounts,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Bar chart for Status
const ctx2 = document.getElementById('statusChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Pending', 'Completed'],
        datasets: [{
            label: 'Stake Status',
            data: [statusCount.pending, statusCount.completed],
            backgroundColor: ['#ffc107', '#28a745'],
            borderColor: ['#e0a800', '#218838'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.raw}`;
                    }
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
