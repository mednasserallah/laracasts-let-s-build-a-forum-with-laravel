{{--Editing the thread--}}
<div class="card mb-4" v-if="editing">
    <div class="card-header">

        <div class="form-group">
            <input type="text" class="form-control" v-model="form.title">
        </div>

    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="8" v-model="form.body"></textarea>
        </div>
    </div>

    @can ('update', $thread)
        <div class="card-footer">
            <button class="btn btn-outline-primary" @click="editOrCancel" v-text="editing ? 'Cancel' : 'Edit'"></button>

            <button v-if="editing" @click="update" class="btn btn-primary float-right">Update</button>

        </div>
    @endcan

</div>

{{--Showing the thread--}}
<div class="card mb-4" v-else>
    <div class="card-header">

        <img class="float-left mr-3" src="{{ $thread->creator->avatar() }}" alt="user avatar" width="50">

        <div class="float-left" v-text="title"></div>

    </div>

    <div class="card-body">
        <article>
            <div class="card-text" v-text="body"></div>
        </article>
    </div>

    <div class="card-footer">
        @can ('delete', $thread)
            <div class="float-right">
                <form method="POST" action="{{ route('threads.destroy', [$thread->channel->slug, $thread->id]) }}">
                    @method('DELETE')
                    @csrf

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
            <button class="btn btn-outline-primary" @click="editing = true">Edit</button>
        @endcan

    </div>
</div>

