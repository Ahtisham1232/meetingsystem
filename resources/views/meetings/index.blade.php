@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Meetings</h1>
        <a href="{{ route('meetings.create') }}" class="btn btn-primary">Create Meeting</a>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Date/Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($meetings as $meeting)
                    <tr>
                        <td>{{ $meeting->subject }}</td>
                        <td>{{ $meeting->date_time }}</td>
                        <td>
                            <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this meeting?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No meetings available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $meetings->links() }}
    </div>
@endsection
