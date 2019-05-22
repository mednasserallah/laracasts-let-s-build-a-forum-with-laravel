@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header mb-3">
                    <h1>{{ $user->name }}</h1>
                    <small>Member since {{ $user->created_at->diffForHumans() }}</small>

                    @can ('update', $user)
                        <form method="POST" action="{{ route('users.avatars', $user) }}" enctype="multipart/form-data">
                            @csrf

                            <input type="file" name="avatar">

                            <button type="submit" class="btn btn-primary">Add Avatar</button>
                        </form>
                    @endcan

                    <img src="/storage/{{ $user->avatar_path }}" alt="user avatar" width="50">
                </div>

                @forelse ($records as $date => $activities)

                    <div>
                        <h3>{{ $date }}</h3>
                        @foreach($activities as $activity)
                            @if (view()->exists("profiles.activities.{$activity->type}"))
                                @include ("profiles.activities.{$activity->type}")
                            @endif
                        @endforeach

                    </div>
                    <hr>
                @empty
                    <p>There is no activity for this user yet.</p>
                @endforelse

                {{--{{ $activities->links() }}--}}
            </div>
        </div>
    </div>
@endsection
