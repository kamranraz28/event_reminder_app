<!DOCTYPE html>
<html>

<head>
    <title>Event Reminder</title>
</head>

<body>
    <h2>Just a reminder of the recently creted event!</h1>
        <h3>{{ $title }}</h1>
            <p>{{ $description }}</p>
            <p><strong>Event Date:</strong> {{ \Carbon\Carbon::parse($event_date)->format('F j, Y, g:i a') }}</p>
</body>

</html>
