@extends('layouts.app')

@section('content')
<div class="container">
    @include('errors.error')
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
                <td><div class="btn  btn-sm btn-{{$meeting->status==='Closed'?'danger':'secondary'}}">{{$meeting->status}}</div></td>
                <td colspan="2">
                    <a href="{{route('meetings.show', [$meeting->id])}}"><button type="button" class="btn btn-secondary btn-sm">View</button></a>
                    <a href="{{route('meetings.edit', [$meeting->id])}}"><button type="button" class="btn btn-warning btn-sm">Edit</button></a>
                    <form action="{{route('meetings.destroy' , [$meeting->id])}}" method="post" style="display:inline;">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
            {{ $meetings->links() }}
        </table>

    </div>
</div>
@endsection
