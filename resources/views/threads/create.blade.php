@extends('layouts.app')

@section('head')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Thread</div>

                    <div class="card-body">
                        @if($errors->count())
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ route('threads.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="channel-id-input">Choose a Channel:</label>
                                <select class="form-control" name="channel_id" id="channel-id-input" required>
                                    <option value="">Choose One...</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title-input">Title</label>
                                <input type="text" name="title" class="form-control"  value="{{ old('title') }}" id="title-input" required>
                            </div>

                            <div class="form-group">
                                <label for="body-input">Body</label>
                                <textarea class="form-control" name="body" id="body-input" rows="3" required>{{ old('body') }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                            </div>

                            <button type="submit" class="btn btn-primary mb-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
