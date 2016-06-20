<?php
return array(
  /*
  |--------------------------------------------------------------------------
  | Textmagic Configuration
  |--------------------------------------------------------------------------
  | To start sending text messages using the TextMagic REST API, just follow these steps:
  |
  | 1. Generate the API credentials - https://my.textmagic.com/online/api/rest-api/keys
  | 2. Connect to our API endpoint
  |
  | To start sending text messages, you need to create an API key. API keys
  | are similar to an account password; the difference is that an API key
  | only provides access to the API: you cannot log in to TextMagic Online
  | using the API key.
  |
  | Your program sends the login credentials with each API request as HTTP headers: X-TM-Username is your TextMagic username, while X-TM-Key is your API key.
  |
  | Please configure your username and token below.
  |
  */

  'username' =>  env('TEXTMAGIC_USERNAME'),
  'token' => env('TEXTMAGIC_TOKEN'),
  //'version' => null,
  //'http' => null,
);
