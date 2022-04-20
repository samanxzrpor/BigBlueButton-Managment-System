@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('errors.error')
            <table class="table">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">User Name</th>
                    <th scope="col">User Session</th>
                </tr>
                </thead>
                <tbody>
                @foreach($attendance as $users)
                <tr>
                    <th scope="row"></th>
                    <td>{{ $users['name']  }}</td>
                    <td>{{ $users['user_session'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
