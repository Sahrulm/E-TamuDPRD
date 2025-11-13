<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function sendPassword($nama,$phone, $msg) {
        

         $response = Http::withHeaders([
             'Authorization' => env('FONNTE_API_KEY'),
         ])->post('https://api.fonnte.com/send', [
             'target' => $phone,
             'message' => $msg
         ])->json();
         }
}
