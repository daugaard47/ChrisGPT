<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function fineTune(Request $request)
    {
        return view('chat.fine-tune');
    }

}
