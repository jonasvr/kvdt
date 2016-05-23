<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Google_Client;
use Google_Service_Calendar;
use Cookie;
use Auth;

class TestController extends Controller
{

    public function Test3(){

       $client = new Google_Client();
       $client->setClientId(env('GOOGLE_APP_ID'));
       $client->setClientSecret(env('GOOGLE_APP_SECRET'));
       $client->setRedirectUri('http://kvdt.dev/test3');
       $client->setAccessType('offline');   // Gets us our refreshtoken

       $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));


         // Step 2: The user accepted your access now you need to exchange it.
        if (isset($_GET['code'])) {
         	$client->authenticate($_GET['code']);
         	$token = $client->getAccessToken();
        }

        if (isset($token)) {
            $client->setAccessToken($token);
            $service = new Google_Service_Calendar($client);
            $calendarList  = $service->calendarList->listCalendarList();;

            $calendars =array();
            while(true) {
                // echo "<h1> select calendars to follow </h1>";
                foreach ($calendarList->getItems() as $calendarListEntry) {

                    // echo $calendarListEntry->getSummary()." - ". $calendarListEntry->id."<br>\n"; //=> naam kalender
                    $calendars[$calendarListEntry->id] = $calendarListEntry->getSummary();
                       // get events
                    // $events = $service->events->listEvents($calendarListEntry->id);
                    // dd($events->getItems());
                    // foreach ($events->getItems() as $event) {
                    //     $pieces = explode("T", $event->start->dateTime);
                    //     $startDate = $pieces[0];
                    //
                    //     $pieces = explode("+", $pieces[1]);
                    //     $startTime = $pieces[0];
                    //     echo $startDate." - " .$startTime . "-----".$event->getSummary()."<br>";
                    // }
                }
                $pageToken = $calendarList->getNextPageToken();
               if ($pageToken) {
                    $optParams = array('pageToken' => $pageToken);
                    $calendarList = $service->calendarList->listCalendarList($optParams);
               } else {
                    break;
               }
           }

           $data = [
               'calendarList' => $calendars,
           ];
           return view('setup.calendar',$data);
       }

        // Step 1:  The user has not authenticated we give them a link to login
        if (!isset($token)) {
            $authUrl = $client->createAuthUrl();
            return Redirect($authUrl);
        }
    }
}
