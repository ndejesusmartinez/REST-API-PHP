<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\PHPIMAP\Client;

class correosController extends Controller
{
    //
    public function leerCorreos(){
        
        // $hostname = '{imap.gmail.com:993/ssl}[Gmail]/Destacados';
        // $username = env('APP_EMAIL_LEER');
        // $password = env('APP_EMAIL_PASSWORD');
        // $mbox   = imap_open("{$hostname}", $username , $password )   or  die("can't connect: " . imap_last_error());
        // $emails = imap_search($mbox,'UNSEEN');

        $data = new Client();
        dd($data->connect());

    }
}
