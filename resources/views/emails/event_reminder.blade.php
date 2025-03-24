<!DOCTYPE html>
<html>
<head>
    <title>Event Reminder</title>
</head>
<body>
    <h2>Just a Reminder for Event</h2>
    <h3>You have an event tomorrow!</h3>
    <p>{{ $event->description }}</p>
    <p>Date & Time: {{ $event->event_date }}</p>
    <p>Thank you!</p>
</body>
</html>
