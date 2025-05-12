@extends('backend.layouts.app', ['isBanner' => false])

@section('title') {{ 'Subscriptions' }} @endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Subscriptions') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ setBaseUrlWithFileName($subscription->user->file_url) ?? default_user_avatar() }}" 
                                                 alt="avatar" 
                                                 class="avatar avatar-40 rounded-pill">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $subscription->user->name }}</h6>
                                                <small>{{ $subscription->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $subscription->plan->name }}</td>
                                    <td>{{ Currency::format($subscription->amount) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $subscription->start_date->format('d M Y') }}</td>
                                    <td>{{ $subscription->end_date->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('producer.subscriptions.show', $subscription) }}" class="btn btn-sm btn-info">
                                            <i class="ph ph-eye"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No subscriptions found') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 