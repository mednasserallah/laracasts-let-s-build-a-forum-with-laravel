@forelse ($threads as $thread)
    <div class="card mb-4">
        <div class="card-header">
            Posted by
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
                    <a href="{{ route('threads.show', [ $thread->channel->slug, $thread->slug ]) }}">
                        @if ($thread->hasUpdates())
                            <strong>
                                {{ $thread->title }}
                            </strong>
                        @else
                            {{ $thread->title }}
                        @endif
                    </a>
                </h4>

                <div class="card-text">{{ $thread->body }}</div>

            </article>
        </div>

        @if ($thread->visits()->count())
            <div class="card-footer">
                {{ $thread->visits()->count() }} views
            </div>
        @endif
    </div>
@empty
    <div class="alert alert-light" role="alert">
        There are no relevant result at this time.
    </div>
@endforelse
