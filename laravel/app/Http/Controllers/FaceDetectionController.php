<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaceDetectionController extends Controller
{
    public function index()
    {
        return view('face-detection');
    }
}
