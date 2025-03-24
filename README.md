App Name: Event Reminder App
Description: A fully-featured event reminder system allowing users to view, create, edit, and delete events with CSV upload. When offline, events are stored locally and synced once the internet is restored. After event creation, guests receive a confirmation email, and a reminder email is sent before the event date.

Developed by:
Name: MD Kamran Hosan
E-mail: mdkamranhosan98@gmail.com
Phone: +880 1609758377

Live App: https://b68a-103-59-179-1.ngrok-free.app/

Instructions Before Using the App:

Access Permission: Since this is hosted on ngrok (free hosting), you’ll be prompted with a permission request. Click on “Visit Site” to access the app.

Create New Event: After creating a new event, the guest will receive an email confirming the event.

Offline Event Creation: If there’s no internet connection during event creation, the event will be temporarily stored in Laravel’s built-in cache. Once the internet connection is back, the event will sync and the guest will receive the confirmation email.

Event Reminder: The system sends a reminder email to event guests a day before the event. For testing, you can create an event for the next day and wait for a reminder email (Note: the reminder will be sent daily at a specific time in production; for testing, it’s set for 5 minutes).

How to Pull the Project and Run Locally:

Prerequisite: 
PHP = 8.0 or Above
Laravel = 10

Clone the Repository:
git clone https://github.com/kamranraz28/covid-vaccine-registration.git

Install Dependencies: Ensure that you have Composer installed, then run:
composer install

Set up Environment Variables: 
Rename - Copy.env to .env
Then configure your database and in the .env file.

Run Migrations:
php artisan migrate

Serve the Application:
php artisan serve

Visit the App: Open your browser and visit http://127.0.0.1:8000 to access the app locally.

To run the scheduler:
php artisan schedule:work

Now you're all set to run the Event Reminder App on your local machine! Let me know if you need further assistance.
