@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header mb-3">
                    <avatar-form :user="{{ $user }}"></avatar-form>
                    <small>Member since {{ $user->created_at->diffForHumans() }}</small>
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
