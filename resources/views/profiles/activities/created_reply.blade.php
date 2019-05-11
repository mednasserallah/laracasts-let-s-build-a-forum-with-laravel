@component('profiles.activities.activity')
    @slot('heading')
        You replied to
        <a href="{{ route('threads.show', [ $activity->subject->thread->channel->slug, $activity->subject->thread->id ]) }}">
            {{ $activity->subject->thread->title }}
        </a>
        <div class="float-right">
            {{ $activity->subject->created_at->diffForHumans() }}.
        </div>
    @endslot

    @slot('body')
        <div class="card-text">{{ $activity->subject->body }}</div>
    @endslot
@endcomponent
