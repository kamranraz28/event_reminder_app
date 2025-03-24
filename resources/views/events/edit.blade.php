@extends('layouts.app')

@section('title', 'Edit Event Reminder')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Event Reminder</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('events.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Event Date</label>
                <input type="datetime-local" name="event_date" class="form-control"
                    value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Emails (comma-separated)</label>
                <input type="text" name="emails" class="form-control" value="{{ old('emails', implode(',', json_decode($event->emails, true))) }}">
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
