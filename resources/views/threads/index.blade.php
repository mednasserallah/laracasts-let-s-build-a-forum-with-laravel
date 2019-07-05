@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-8">

                @include('threads._list')

                {{ $threads->links() }}
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <form method="GET" action="/threads/search">
                            <div class="row form-group d-inline">
                                <input type="text" class="form-control float-left col-8" name="q" placeholder="Search for something...">
                                <button type="submit" class="btn btn-primary float-right">Search</button>
                            </div>
                        </form>

                    </div>
                </div>

                @if ($trending)
                    <div class="card mb-4">
                        <div class="card-header">
                            Trending Threads
                        </div>

                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($trending as $thread)
                                    <li class="list-group-item">
                                        <a href="{{ url($thread->path) }}">
                                            {{ $thread->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
