@extends('backend.layouts.app', ['isBanner' => false])

@section('title') {{ 'Producer Dashboard' }} @endsection

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('producer.users.index') }}">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-5 fs-1">
                                    <i class="ph ph-user"></i>
                                </div>
                                <div class="card-data">
                                    <h1 class="">{{ $totalusers ?? 0 }}</h1>
                                    <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_users') }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('producer.subscriptions.index') }}">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-5 fs-1">
                                    <i class="ph ph-users-three"></i>
                                </div>
                                <div class="card-data">
                                    <h1 class="">{{ $totalSubscribers ?? 0 }}</h1>
                                    <p class="mb-0 fs-6">{{ __('dashboard.lbl_total_subscribers') }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('producer.reviews.index') }}">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-5 fs-1">
                                    <i class="ph ph-code-block"></i>
                                </div>
                                <div class="card-data">
                                    <h1 class="">{{ $totalreview ?? 0 }}</h1>
                                    <p class="mb-0 fs-6">{{ __('dashboard.lbl_review') }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-stats">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dashboard.lbl_top_genres') }}</h3>
                </div>
                <div class="card-body">
                    <div id="chart-top-genres"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stats card-block card-height">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h3 class="card-title">{{ __('dashboard.lbl_tot_revenue') }}</h3>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle total_revenue" type="button" id="dropdownTotalRevenue" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('dashboard.year') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownTotalRevenue">
                            <li><a class="revenue-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                            <li><a class="revenue-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                            <li><a class="revenue-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-top-revenue"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stats card-block card-height">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h3 class="card-title">{{ __('dashboard.new_subscribers') }}</h3>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle total_subscribers" type="button" id="dropdownNewSubscribers" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('dashboard.year') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownNewSubscribers">
                            <li><a class="subscribers-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                            <li><a class="subscribers-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                            <li><a class="subscribers-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-new-subscription"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const formatCurrencyvalue = (value) => {
        if (window.currencyFormat !== undefined) {
            return window.currencyFormat(value)
        }
        return value
    }

    document.addEventListener('DOMContentLoaded', function() {
        var Base_url = "{{ url('/') }}";
        var url = Base_url + "/producer/get_genre_chart_data";

        $.ajax({
            url: url,
            method: "GET",
            data: {},
            success: function(response) {
                if (document.querySelectorAll('#chart-top-genres').length) {
                    const chartData = response.data.chartData;
                    const category = response.data.category;
                    const options = {
                        series: chartData,
                        chart: {
                            height: 255,
                            type: 'donut',
                        },
                        stroke: {
                            width: 0,
                        },
                        colors: ['var(--bs-primary)', 'var(--bs-primary-tint-20)', 'var(--bs-primary-tint-40)', 'var(--bs-primary-tint-60)', 'var(--bs-primary-tint-80)'],
                        labels: category,
                        dataLabels: {
                            enabled: false,
                        },
                        legend: {
                            show: true,
                            position: 'bottom',
                            fontSize: '14px',
                            labels: {
                                colors: ['var(--bs-white)', 'var(--bs-white)', 'var(--bs-white)', 'var(--bs-white)', 'var(--bs-white)']
                            },
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart-top-genres"), options);
                    chart.render();
                }
            }
        });
    });

    // Initialize charts
    revanue_chart('Year');
    subscriber_chart('Year');

    let revenueInstance;

    function revanue_chart(type) {
        var Base_url = "{{ url('/') }}";
        var url = Base_url + "/producer/get_revnue_chart_data/" + type;

        $.ajax({
            url: url,
            method: "GET",
            data: {},
            success: function(response) {
                $(".total_revenue").text(type);
                if (document.querySelectorAll('#chart-top-revenue').length) {
                    const chartData = response.data.chartData;
                    const category = response.data.category;
                    const options = {
                        series: [{
                            name: 'Revenue',
                            data: chartData
                        }],
                        chart: {
                            type: 'area',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        xaxis: {
                            categories: category,
                            labels: {
                                style: {
                                    colors: 'var(--bs-white)'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: 'var(--bs-white)'
                                },
                                formatter: function(value) {
                                    return formatCurrencyvalue(value);
                                }
                            }
                        },
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: function(value) {
                                    return formatCurrencyvalue(value);
                                }
                            }
                        },
                        grid: {
                            borderColor: 'var(--bs-border-color)',
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.7,
                                opacityTo: 0.3,
                                stops: [0, 90, 100]
                            }
                        }
                    };

                    if (revenueInstance) {
                        revenueInstance.updateOptions(options);
                    } else {
                        revenueInstance = new ApexCharts(document.querySelector("#chart-top-revenue"), options);
                        revenueInstance.render();
                    }
                }
            }
        });
    }

    let subscriberInstance;

    function subscriber_chart(type) {
        var Base_url = "{{ url('/') }}";
        var url = Base_url + "/producer/get_subscriber_chart_data/" + type;

        $.ajax({
            url: url,
            method: "GET",
            data: {},
            success: function(response) {
                $(".total_subscribers").text(type);
                if (document.querySelectorAll('#chart-new-subscription').length) {
                    const chartData = response.data.chartData;
                    const category = response.data.category;
                    const options = {
                        series: [{
                            name: 'Subscribers',
                            data: chartData
                        }],
                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                horizontal: false,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        xaxis: {
                            categories: category,
                            labels: {
                                style: {
                                    colors: 'var(--bs-white)'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: 'var(--bs-white)'
                                }
                            }
                        },
                        grid: {
                            borderColor: 'var(--bs-border-color)',
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark',
                                type: "vertical",
                                shadeIntensity: 0.5,
                                gradientToColors: undefined,
                                inverseColors: true,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };

                    if (subscriberInstance) {
                        subscriberInstance.updateOptions(options);
                    } else {
                        subscriberInstance = new ApexCharts(document.querySelector("#chart-new-subscription"), options);
                        subscriberInstance.render();
                    }
                }
            }
        });
    }

    // Event listeners
    $(document).on('click', '.revenue-dropdown-item', function() {
        var type = $(this).data('type');
        revanue_chart(type);
    });

    $(document).on('click', '.subscribers-dropdown-item', function() {
        var type = $(this).data('type');
        subscriber_chart(type);
    });
</script>
@endpush 