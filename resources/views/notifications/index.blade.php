@extends('technician.dashboard.layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>My Notifications</span>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <a href="{{ route('notifications.markAllAsRead') }}" class="btn btn-sm btn-outline-primary"
                                onclick="event.preventDefault(); document.getElementById('mark-all-as-read-form').submit();">
                                Mark all as read
                            </a>
                            <form id="mark-all-as-read-form" action="{{ route('notifications.markAllAsRead') }}"
                                method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </div>

                    <div class="card-body">
                        @if ($notifications->count() > 0)
                            <div class="list-group">
                                @foreach ($notifications as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}"
                                        class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                        @if (!$notification->read_at)
                                            <form method="POST"
                                                action="{{ route('notifications.markAsRead', $notification->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-link p-0">Mark as read</button>
                                            </form>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <p class="mb-0">No notifications found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
