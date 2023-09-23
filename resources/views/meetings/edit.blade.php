@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Meeting</h1>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('meetings.update', $meeting) }}">
            @csrf
            @method('PUT') <!-- Use the PUT method for updates -->

            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" class="form-control" value="{{ $meeting->subject }}" required>
            </div>

            <div class="form-group">
                <label for="date_time">Date/Time:</label>
                <input type="datetime-local" name="date_time" id="date_time" class="form-control" value="{{ old('date_time', $meeting->date_time) }}" required>
            </div>

            <div class="form-group">
                <label for="email_attendess_one">Attendee 1 Email:</label>
                <input type="email" name="email_attendess_one" id="email_attendess_one" class="form-control" value="{{ old('email_attendess_one', $meeting->attendees->first()->email_attendess_one) }}" required>
            </div>

            <div class="form-group">
                <label for="email_attendess_two">Attendee 2 Email:</label>
                <input type="email" name="email_attendess_two" id="email_attendess_two" class="form-control" value="{{ old('email_attendess_two', $meeting->attendees->last()->email_attendess_two) }}" required>
            </div>

            <!-- Other form fields for editing -->

            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Update Meeting</button>
            </div>
        </form>
    </div>
@endsection
