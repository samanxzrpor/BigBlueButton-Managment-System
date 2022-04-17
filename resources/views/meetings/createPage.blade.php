@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('errors.error')
        <h2>Create New Seminar Session</h2>
        <form class="form-control" action="{{route('meetings.store')}}" method="post" style="background-color:#fff">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title of Meeting</label>
                <input class="form-control" name="title" type="text" placeholder="Title of Meeting">
            </div>
            <div class="mb-3">
                <label for="start-dateTime" class="form-label">Date and Time for Start Meeting</label>
                <input class="form-control" name="start-dateTime" type="datetime-local" placeholder="Default input">
            </div>
            <div class="mb-3">
                <label for="during-time" class="form-label">During Time Meeting(0 - 24)</label>
                <input class="form-control" name="during-time" type="range" min="0" max="24">
            </div>
            <div class="form-check form-switch" style="margin-bottom: 1em">
                <input class="form-check-input" type="checkbox" name="recording">
                <label class="form-check-label" for="flexSwitchCheckDefault">Record Meet</label>
            </div>
            <div class="form-check form-switch" style="margin-bottom: 1em">
                <input class="form-check-input" type="checkbox" name="need_pass">
                <label class="form-check-label" for="flexSwitchCheckDefault">Need Password</label>
            </div>
            <div class="mb-3">
                <input class="form-control btn btn-success" type="submit" value="Save">
            </div>
        </form>
    </div>
</div>
@endsection
