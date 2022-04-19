@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('errors.error')
            <h2>Edit {{$meeting->title}}</h2>
            <form class="form-control" action="{{route('meetings.update' , [$meeting->id])}}" method="post" style="background-color:#fff">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Title of Meeting</label>
                    <input class="form-control" name="title" type="text" value="{{$meeting->title}}">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Date and Time for Start Meeting</label>
                    <input class="form-control" name="start-dateTime" type="datetime-local" value="{{str_replace(' ' , 'T',$meeting->start_meeting_time)}}">
                </div>

                <div class="mb-3">
                    <label for="during-time" class="form-label">During Time Meeting(Minute)</label>
                    <input class="form-control" name="during-time" value="{{$meeting->during_time}}" type="number">
                </div>
                <div class="form-check form-switch" style="margin-bottom: 1em">
                    <input class="form-check-input" type="checkbox" {{unserialize($meeting->meeting_data)['recording']==='on' ? 'checked' : ''}} name="recording">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Record Meet</label>
                </div>
                <div class="mb-3">
                    <input class="form-control btn btn-success" type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>
@endsection
