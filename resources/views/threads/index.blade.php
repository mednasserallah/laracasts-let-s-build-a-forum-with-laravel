@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse ($threads as $thread)
                    <div class="card mb-4">
                        <div class="card-header">
                            <a href="{{ route('profile.show', $thread->creator->name) }}">
                                {{ $thread->creator->name }}
                            </a>

                            <a class="float-right" href="{{ route('threads.show', [ $thread->channel->slug, $thread->id ]) }}">
                                <strong>{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</strong>
                            </a>
                        </div>

                        <div class="card-body">
                            <article>

                                <h4 class="card-title flex">
                                    <a href="{{ route('threads.show', [ $thread->channel->slug, $thread->id ]) }}">
                                        {{ $thread->title }}
                                    </a>
                                </h4>

                                <div class="card-text">{{ $thread->body }}</div>
                            </article>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light" role="alert">
                        There are no relevant result at this time.
                    </div>
                @endforelse

            </div>
        </div>
    </div>
@endsection
