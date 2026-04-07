<?php

namespace App\Http\Controllers;

class WhatsAppController extends Controller
{
    public function config()
    {
        return view('whatsapp.config');
    }

    public function messages()
    {
        return view('whatsapp.messages');
    }

    public function ai()
    {
        return view('whatsapp.ai');
    }
}
