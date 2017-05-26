## About Twilio Autodialer

The Twilio Autodialer is built on [Laravel 5.3](https://laravel.com).  It uses [Twilio](https://www.twilio.com) to place automated calls to phone numbers that you provide.

You can register for an account [here](http://autodialer.karmatek.io/register), but you'll need a Twilio account first (see below).

##Features

- Send calls via SMS or Voice
- Type in custom messages to be sent via SMS or spoken via voice call.
- Load custom audio files to be played when voice calls are answered.
- Use the bulk interface for dialing lots of numbers.
- Process call logs from any gateway to confirm your call has arrived (handy for PBX number porting)

## What you will need to get started

All you need to use the Twilio Autodialer is a Twilio account and Twilio verified phone number.  You can register for a Twilio account [here](https://www.twilio.com/try-twilio).
Once you have your account created and obtain a verified phone number, you can register for an account with the Autodialer.  It will ask for your Twilio SID and Token.
Now you can place calls with the Autodialer, which will be charged against your Twilio account.

## Bulk Dialer
The bulk dialer is the main feature of the Autodialer since it enables you to place automated calls to a large list of phone numbers.  You can select your Caller ID from the list of
verified phone numbers, choose whether you would like to place an SMS or voice call, or even play an audio file back when the call is answered.

## Log Processing
The log processing feature comes in handy when large enterprises are moving phone numbers between providers or connection types.  The Autodailer will accept one or many log files
which will be parsed to provide a report on your bulk dialer process.

For example:
If you process a bulk dialer file of 1,000 phone numbers, you could collect the log files from the PSTN gateway the calls should come in on (SIP, H323 or other) and then load those files to
the Autodialer.  It will compare your bulk dialer file (phone numbers) against the logs files and provide a report on whether those numbers appear in the log files.