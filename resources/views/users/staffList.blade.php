@extends('users.layout')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <h2>Staff List</h2>
        </div>
        <div class="col-lg-6 text-right">
            <a class="btn btn-success" href="{{ route('users.create') }}">Add Staff</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Staff Name</th>
            <th>Email</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($users as $user)
           
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                            <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>
                            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            
        @endforeach

    </table>
    {{ $users->links() }}

@endsection
