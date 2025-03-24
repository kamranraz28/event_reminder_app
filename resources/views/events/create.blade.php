@extends('layouts.app')

@section('title', 'Add Event Reminder')

@section('content')
    <div class="container">
        <h2 class="mb-4">Add Event Reminder</h2>

        <!-- CSV Import Form -->
        <div class="mb-4">
            <h4>Import Event Reminders from CSV</h4>
            <form class="form-horizontal" method="POST" action="{{ route('events.import') }}" autocomplete="on" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Upload CSV File</label>
                    <input id="csv_file" type="file" class="form-control" name="csv_file" required>
                </div>
                <button class="btn btn-primary">Import CSV</button>
            </form>
        </div>
        <br>
        <br>

        <!-- Add Event Reminder Form -->
        <form action="{{ route('events.store') }}" method="POST" id="eventForm">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Event Date</label>
                <input type="datetime-local" name="event_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Emails (comma-separated)</label>
                <input type="text" name="emails" class="form-control">
            </div>

            <button class="btn btn-success">Save</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>

@endsection
