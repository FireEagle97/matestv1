@extends('backend.layouts.app', ['isBanner' => false])

@section('title') {{ 'Reviews' }} @endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Reviews') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Content') }}</th>
                                    <th>{{ __('Rating') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ setBaseUrlWithFileName($review->user->file_url) ?? default_user_avatar() }}" 
                                                 alt="avatar" 
                                                 class="avatar avatar-40 rounded-pill">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                <small>{{ $review->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ setBaseUrlWithFileName($review->entertainment->thumbnail) }}" 
                                                 alt="thumbnail" 
                                                 class="avatar avatar-40 rounded">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $review->entertainment->title }}</h6>
                                                <small>{{ ucfirst($review->entertainment->type) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">
                                                    <i class="ph ph-fill ph-star"></i>
                                                </span>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>{{ $review->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('producer.reviews.show', $review) }}" class="btn btn-sm btn-info">
                                            <i class="ph ph-eye"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('No reviews found') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-styles')
<style>
    .star-rating {
        display: flex;
    }
    .star {
        font-size: 1.2rem;
        color: var(--bs-border-color);
        margin-right: 2px;
    }
    .star.filled {
        color: var(--bs-warning);
    }
</style>
@endpush 