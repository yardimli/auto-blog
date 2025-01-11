<?php

namespace App\Http\Controllers;

use App\Models\ReleaseNote;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReleaseController extends Controller
{

  public $release;

  public function create()
  {
    return view('user.release');
  }

  public function store(Request $request)
  {

    $validated = $request->validate([
      'title' => 'required|max:255',
      'body' => 'required',
      'is_released' => 'boolean'
    ]);

    // Add user_id to the validated data
    $validated['user_id'] = auth()->id();

    $release = ReleaseNote::create($validated);

    return redirect()->route('releases.create')
      ->with('success', __('Release created successfully.'));

  }

}