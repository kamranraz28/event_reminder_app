<?php

namespace App\Http\Controllers;

use App\Models\EventReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Mail\EventReminderCreated;
use Illuminate\Support\Facades\Mail;

class EventReminderController extends Controller
{
    //
    public function index()
    {
        $events = EventReminder::orderBy('event_date')->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'emails' => 'nullable|string',
        ]);

        try {
            $response = Http::get('https://www.google.com'); // Check if external URL is reachable.

            if ($response->successful()) {
                // Retrieve the last event_id and ensure it follows the even number pattern
                $last_event_id = EventReminder::orderBy('id', 'desc')->pluck('event_id')->first();
                $last_event_number = $last_event_id ? (int)explode('-', $last_event_id)[1] : 0;

                // Ensure the event number is even
                $next_event_number = $last_event_number + 2; // Increment by 2 to ensure the next number is even

                // Generate the event_id
                $event_id = 'EV-' . $next_event_number . '-' . time();

                // Create the event reminder
                $eventReminder = EventReminder::create([
                    'event_id' => $event_id,
                    'title' => $request->title,
                    'description' => $request->description ?? '', // Ensure description exists (could be null or empty)
                    'event_date' => $request->event_date,
                    'emails' => $request->emails ? json_encode(explode(',', $request->emails)) : null,
                ]);

                if ($eventReminder->emails) {
                    $emails = json_decode($eventReminder->emails);
                    foreach ($emails as $email) {
                        Mail::to($email)->send(new EventReminderCreated($eventReminder));
                    }
                }
            }
        } catch (\Exception $e) {
            // Internet is not available, cache the event data
            $this->cacheEventData($request);

            // Also store the cache key in a list of keys
            $cachedKeys = Cache::get('event_reminder_keys', []);
            $cachedKeys[] = 'event_reminder_' . time(); // Add the key to the list
            Cache::put('event_reminder_keys', $cachedKeys, now()->addMinutes(60));

            return redirect()->route('events.index')->with('error', 'No internet connection. Your event will be saved once the connection is restored.');
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }



    //cache event when internet is not there
    protected function cacheEventData(Request $request)
    {
        $eventData = [
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'emails' => $request->emails ? json_encode(explode(',', $request->emails)) : null,
        ];

        // Cache the event data with a unique key
        $cacheKey = 'event_reminder_' . time();
        Cache::put($cacheKey, $eventData, now()->addMinutes(60)); // Cache for 1 hour

        // Store the cache key in a separate key list
        $cachedKeys = Cache::get('event_reminder_keys', []);
        $cachedKeys[] = $cacheKey;
        Cache::put('event_reminder_keys', $cachedKeys, now()->addMinutes(60)); // Update the list of cache keys
    }

    //store cache when click on the button
    public function syncEvents()
    {
        try {
            $response = Http::get('https://www.google.com'); // Check if external URL is reachable.

            if ($response->successful()) {
                // Retrieve the stored cache keys
                $cachedKeys = Cache::get('event_reminder_keys', []);

                // Check if there are no cached keys
                if (empty($cachedKeys)) {
                    return redirect()->back()->with('error', 'No events in cache for now.');
                }

                // Loop through the cached keys and process each one
                foreach ($cachedKeys as $key) {
                    $eventData = Cache::get($key);
                    if ($eventData) {
                        $last_event_id = EventReminder::orderBy('id', 'desc')->pluck('event_id')->first();
                        $last_event_number = $last_event_id ? (int)explode('-', $last_event_id)[1] : 0;

                        // Ensure the event number is even
                        $next_event_number = $last_event_number + 2; // Increment by 2 to ensure the next number is even

                        // Generate the event_id
                        $event_id = 'EV-' . $next_event_number . '-' . time();

                        // Create the event reminder in the database
                        $eventReminder = EventReminder::create([
                            'event_id' => $event_id, // Or generate a suitable event_id
                            'title' => $eventData['title'],
                            'description' => $eventData['description'],
                            'event_date' => $eventData['event_date'],
                            'emails' => $eventData['emails'],
                        ]);

                        if ($eventReminder->emails) {
                            $emails = json_decode($eventReminder->emails);
                            foreach ($emails as $email) {
                                Mail::to($email)->send(new EventReminderCreated($eventReminder));
                            }
                        }

                        // Remove cached event after it's successfully synced
                        Cache::forget($key);
                    }
                }
            }
        } catch (\Exception $e) {

            return redirect()->route('events.index')->with('error', 'No internet connection. Your event will be saved once the connection is restored.');
        }



        // Clear the list of keys after syncing
        Cache::forget('event_reminder_keys');

        return redirect()->back()->with('success', 'Event created successfully from cache!');
    }




    public function edit(EventReminder $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, EventReminder $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'emails' => 'nullable|string',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'emails' => $request->emails ? json_encode(explode(',', $request->emails)) : null,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(EventReminder $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Get the path of the uploaded CSV file
        $path = $request->file('csv_file')->getRealPath();

        // Read the file and skip empty lines
        $row_index = file($path, FILE_SKIP_EMPTY_LINES);

        // Convert the file content into an array of CSV data
        $data = array_map('str_getcsv', $row_index);

        // Slice the data to skip the header (first row)
        $csv_data = array_slice($data, 1);

        // Retrieve the last event_id and calculate the next even event number
        $last_event_id = EventReminder::orderBy('id', 'desc')->pluck('event_id')->first();
        $last_event_number = $last_event_id ? (int)explode('-', $last_event_id)[1] : 0;

        // Ensure the next event number is even
        $next_event_number = $last_event_number + 2; // Increment by 2 to ensure the next number is even

        foreach ($csv_data as $row) {
            // Extract the values from each row
            $title = $row[0];
            $description = $row[1];
            $event_date = $row[2];
            $emails = $row[3];

            // Validate the CSV data
            $validator = Validator::make([
                'title' => $title,
                'description' => $description,
                'event_date' => $event_date,
                'emails' => $emails,
            ], [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_date' => 'required|date',
                'emails' => 'nullable|string',
            ]);

            // If validation fails, skip this row and continue to the next one
            if ($validator->fails()) {
                continue;
            }

            // Generate the event_id with an even number
            $event_id = 'EV-' . $next_event_number . '-' . time();

            // Create and store the event reminder in the database
            EventReminder::create([
                'event_id' => $event_id,
                'title' => $title,
                'description' => $description,
                'event_date' => $event_date,
                'emails' => json_encode(explode(',', $emails)), // Store emails as a JSON array
            ]);

            // Increment the event number for the next row
            $next_event_number += 2; // Increment by 2 to ensure even numbers
        }

        // Redirect with a success message
        return redirect()->route('events.index')->with('success', 'Events imported successfully!');
    }
}
