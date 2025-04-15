@extends('app')

@section('content')
<div class="container py-4">
    <h1 class="display-4 mb-4">Dashboard</h1>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
        <!-- Stats Cards -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title text-muted">Total Posts</h3>
                    <p class="display-6 fw-bold mb-0">{{ $postCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title text-muted">Bookmarks</h3>
                    <p class="display-6 fw-bold mb-0">{{ $bookmarkCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title text-muted">Comments</h3>
                    <p class="display-6 fw-bold mb-0">{{ $commentCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title text-muted">Users</h3>
                    <p class="display-6 fw-bold mb-0">{{ $userCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity Section -->
    <div class="card">
        <div class="card-body">
            <h2 class="card-title h4 mb-4">Recent Activity</h2>
            <div class="list-group list-group-flush">
                @forelse($activities as $activity)
                    <div class="list-group-item border-bottom">
                        <p class="text-muted mb-1">{{ $activity->description }}</p>
                        <small class="text-secondary">{{ $activity->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection