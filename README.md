## About Twilio Autodialer

The Twilio Autodialer is built on [Laravel 5.4](https://laravel.com). It uses [Twilio](https://www.twilio.com) to place automated calls to phone numbers that you provide.

## Features

- Send calls via SMS or Voice
- Type in custom messages to be sent via SMS or spoken via voice call.
- Load custom audio files to be played when voice calls are answered.
- Use the bulk interface for dialing lots of numbers.
- Process call logs from any gateway to confirm your call has arrived (handy for PBX number porting)

## Local Setup

If you want to run the autodialer local or deploy your own instance, you can follow these steps to get up and running.

- Clone the repository

```bash
git clone https://github.com/sloan58/twilio-autodialer.git
```

- Install PHP dependencies

```bash
composer install
```

- Copy the .env settings

```bash
cp .env.example .env
```

- Generate an application key

```bash
php artisan key:generate
```

- Setup the database (tested with mysql)

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=autodialer
DB_USERNAME=root
DB_PASSWORD=
```

- Migrate the database

```bash
php artisan migrate
```

- Create an Admin user

```bash
php artisan autodialer:create-admin
```

- Run the app locally (optional)

```bash
php artisan serve
```

If you plan to use social auth to login, be sure to fill out the settings in the .env file for Facebook, Google and Twitter auth!

## What you will need to get started

All you need to use the Twilio Autodialer is a Twilio account and at least one Twilio verified phone number. If you have several numbers already, you can pick between them in the UI. You can register for a Twilio account [here](https://www.twilio.com/try-twilio).
Once you have your account created and obtain a verified phone number, you can register for an account with the Autodialer. It will ask for your Twilio SID and Token.
Now you can place calls with the Autodialer, which will be charged against your Twilio account.

## Bulk Dialer

The bulk dialer is the main feature of the Autodialer since it enables you to place automated calls to a large list of phone numbers. You can select your Caller ID from the list of
verified phone numbers, choose whether you would like to place an SMS or voice call, or even play an audio file back when the call is answered.

## Log Processing

The log processing feature comes in handy when large enterprises are moving phone numbers between providers or connection types. The Autodailer will accept one or many log files
which will be parsed to provide a report on your bulk dialer process.

For example:
If you process a bulk dialer file of 1,000 phone numbers, you could collect the log files from the PSTN gateway the calls should come in on (SIP, H323 or other) and then load those files to
the Autodialer. It will compare your bulk dialer file (phone numbers) against the log files and provide a report on whether those numbers appear in the log files.
