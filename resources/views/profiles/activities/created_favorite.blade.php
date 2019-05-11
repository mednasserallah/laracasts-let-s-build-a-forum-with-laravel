@component('profiles.activities.activity')
    @slot('heading')
        You favorited a
        <a href="{{ $activity->subject->favorited->path() }}">
            reply.
        </a>
        <div class="float-right">
            {{ $activity->subject->created_at->diffForHumans() }}.
        </div>
    @endslot

    @slot('body')
        <div class="card-text">{{ $activity->subject->favorited->body }}</div>
    @endslot
@endcomponent
