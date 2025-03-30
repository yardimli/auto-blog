<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoadmapController extends Controller
{


  public function index(Request $request)
  {

    return view('roadmap.index');
  }


}
