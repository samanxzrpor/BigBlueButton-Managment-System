@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{route('meetings.create')}}"><button type="button" class="btn btn-primary">Create Meeting</button></a>
    <br><br>
    <div class="row justify-content-center">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Meeting Title</th>
                <th scope="col">Start Time</th>
                <th scope="col">Moderator</th>
                <th scope="col">Status</th>
                <th colspan="2" scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($meetings as $meeting)
            <tr>
                <th scope="row">{{$meeting->id}}</th>
                <td>{{$meeting->title}}</td>
                <td>{{$meeting->start_meeting_time}}</td>
                <td>{{$meeting->user->name}}</td>
                <td>{{$meeting->status}}</td>
                <td colspan="2">
                    <button type="button" class="btn btn-secondary">View</button>
                    <button type="button" class="btn btn-warning">Edit</button>
                    <button type="button" class="btn btn-danger">Delete</button>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
