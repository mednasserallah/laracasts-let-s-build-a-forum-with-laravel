@component('profiles.activities.activity')
    @slot('heading')
        You published a thread
        <div class="float-right">
            {{ $activity->subject->created_at->diffForHumans() }}.
        </div>
    @endslot

    @slot('body')
        <div class="card-title">
            <a href="{{ route('threads.show', [ $activity->subject->channel->slug, $activity->id ]) }}">
                {{ $activity->subject->title }}
            </a>

            <a class="float-right" href="{{ route('threads.show', [ $activity->subject->channel->slug, $activity->id ]) }}">
                <strong>{{ $activity->subject->replies_count }} {{ Str::plural('reply', $activity->subject->replies_count) }}</strong>
            </a>
        </div>

        <div class="card-text">{{ $activity->subject->body }}</div>
    @endslot
@endcomponent
