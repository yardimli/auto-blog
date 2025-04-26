<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{


  public function index(Request $request)
  {

    return view('knowledgebase.index');
  }


}
