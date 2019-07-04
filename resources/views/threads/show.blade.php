@extends('layouts.app')

@section('content')
    <thread-view inline-template :thread="{{ $thread }}">
        <div class="container">
            <div class="row ">
                <div class="col col-md-8">
                    @include('threads._thread')

                    <replies @reply-removed="repliesCount--"
                             @reply-added="repliesCount++"
                    ></replies>
                </div>

                <div class="col col-md-4">
                    <div class="card mb-4">
                        {{--<div class="card-header">--}}
                            {{--{{ $thread->title }}--}}
                        {{--</div>--}}

                        <div class="card-body">
                            <div class="card-text">
                                This thread was published
                                {{ $thread->created_at->diffForHumans() }} by
                                <a href="{{ route('profile.show', [ $thread->creator->name ]) }}">
                                    {{ $thread->creator->name }}
                                </a>
                                add currently has <span>@{{ repliesCount }}</span> {{ Str::plural('comment', $thread->replies_count) }}.
                            </div>

                            @auth()
                                <subscribe-button :is-subscribed="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                                <button class="btn mt-2 btn-dark" v-if="authorize('isAdmin')" @click="toggleLock" v-text="isLocked ? 'Unlock' : 'Lock'"></button>
                            @endauth

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </thread-view>
@endsection
