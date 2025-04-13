@extends('app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Stats Cards -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-600">Total Posts</h3>
            <p class="text-2xl font-bold">{{ $postCount ?? 0 }}</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-600">Bookmarks</h3>
            <p class="text-2xl font-bold">{{ $bookmarkCount ?? 0 }}</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-600">Comments</h3>
            <p class="text-2xl font-bold">{{ $commentCount ?? 0 }}</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-600">Users</h3>
            <p class="text-2xl font-bold">{{ $userCount ?? 0 }}</p>
        </div>
    </div>
    
    <!-- Recent Activity Section -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
        <div class="space-y-4">
            @forelse($activities as $activity)
                <div class="border-b pb-2">
                    <p class="text-gray-600">{{ $activity->description }}</p>
                    <p class="text-sm text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-gray-600">No recent activity</p>
            @endforelse
        </div>
    </div>
</div>
@endsection