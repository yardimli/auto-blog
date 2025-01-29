<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChangeLogController extends Controller
{

  public $changelog;

  public function index()
  {

    $changelogs = ChangeLog::with([])
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('changelog.index', compact('changelogs'));
  }

  public function create()
  {
    return view('changelog.create');
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

    ChangeLog::create($validated);

    return redirect()->route('changelogs.create')
      ->with('success', __('Change log created successfully.'));

  }

}