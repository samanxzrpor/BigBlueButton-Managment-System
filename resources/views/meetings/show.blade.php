@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('errors.error')
            <h2>Meeting Title : {{$meeting->title}}</h2>
            <hr><br>
            <h5>Meeting Moderator : {{$meeting->user->name}}</h5>
            <h5>Meeting Start Time : {{$meeting->start_meeting_time}}</h5>
            <h6>Meeting During Time : {{$meeting->during_time}} Minutes</h6>

            <button class="alert alert-{{$meeting->status==='Closed'?'danger':'secondary'}}">{{$meeting->status}}</button>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <a href="{{route('bbb.participants' , [$meeting->id])}}"><button class="btn btn-dark">User Sessions</button></a>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <form action="{{route('meetings.setGuestLink' , [$meeting->id])}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-warning">Make Guest Link</button>
                        </form>
                    </div>
                    <div class="col">
                        @if(!is_null($meeting->guest_link))
                            <div class="alert alert-warning" role="alert">
                                {{$meeting->guest_link}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="fixed-bottom" style="margin:4em 7em">
                @if($meeting->status === 'Waiting')
                    <form action="{{route('bbb.create' , [$meeting->id])}}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success">Create BBB Meeting</button>
                    </form>
                @endif
                @if($meeting->status === 'Performing')
                    @if(isset(unserialize($meeting->meeting_data)['need_password']))
                        <form action="{{route('bbb.join' , [$meeting->id])}}" method="post" style="display: inline;">
                            @csrf
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-3">
                                    <input type="password" name="password" class="form-control" id="inputPassword">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning">Join Meeting</button>
                        </form>
                    @else
                        <form action="{{route('bbb.join' , [$meeting->id])}}" method="post" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning">Join Meeting</button>
                        </form>
                    @endif
                    <form action="{{route('bbb.end' , [$meeting->id])}}" method="get" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger" >End Meeting</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
