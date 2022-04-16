@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('errors.error')
            <h2>Meeting Title : {{$meeting->title}}</h2>
            <hr><br>
            <h5>Meeting Moderator : {{$meeting->user->name}}</h5>
            <h5>Meeting Start Time : {{$meeting->start_meeting_time}}</h5>
            <h6>Meeting During Time : {{$meeting->during_time}} Hour</h6>

            <button class="btn btn-secondary">{{$meeting->status}}</button>
            <div class="fixed-bottom" style="margin:4em 7em">
                @if($meeting->status === 'Waiting')
                    <form action="{{route('bbb.create' , [$meeting->id])}}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success">Create BBB Meeting</button>
                    </form>
                @endif
                @if($meeting->status === 'Performing')
                    <form action="{{route('bbb.join' , [$meeting->id])}}" method="post" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">Join Meeting</button>
                    </form>
                    <form action="{{route('bbb.end' , [$meeting->id])}}" method="post" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger" >End Meeting</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
