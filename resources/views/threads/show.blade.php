@extends('layouts.app')

@section('content')

    <thread-view inline-template :ini-replies-count.number="{{ $thread->replies->count() }}">
        <div class="container">
            <div class="row ">
                <div class="col col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">

                            <div class="float-left">
                                {{ $thread->title }}
                            </div>

                            @can ('delete', $thread)
                                <div class="float-right">
                                    <form method="POST" action="{{ route('threads.destroy', [$thread->channel->slug, $thread->id]) }}">
                                        @method('DELETE')
                                        @csrf

                                        <button type="submit">Delete</button>
                                    </form>
                                </div>
                            @endcan

                        </div>

                        <div class="card-body">
                            <article>
                                <div class="card-text">{{ $thread->body }}</div>
                            </article>
                        </div>
                    </div>

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

                            <subscribe-button :is-subscribed="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </thread-view>
@endsection
