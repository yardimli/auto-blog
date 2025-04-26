<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Help;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{

  public $helps;
  public $categories;

  public function index(Request $request)
  {

    $helps = Help::with(['user', 'category'])
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('helps.index', compact('helps'));
  }

  public function create()
  {
    $categories = Category::where('user_id', auth()->id())->get();
    return view('helps.edit', compact( 'categories'));
  }

  public function edit(Help $help)
  {
    $categories = Category::where('user_id', auth()->id())->get();
    return view('helps.edit', compact('help', 'categories'));
  }

  public function store(Request $request)
  {

    $validated = $request->validate([
      'category_id' => 'required',
      'title' => 'required|max:255',
      'body' => 'required',
      'order' => 'required',
      'is_published' => 'boolean'
    ]);

    // Add user_id to the validated data
    $validated['user_id'] = auth()->id();

    Help::create($validated);

    return redirect()->route('helps.index')
      ->with('success', __('Help Article created successfully.'));

  }

  public function update(Request $request, Help $help)
  {
    $validated = $request->validate([
      'category_id' => 'required',
      'title' => 'required|max:255',
      'body' => 'required',
      'order' => 'required',
      'is_published' => 'boolean'
    ]);

    $help->update($validated);

    return redirect()->route('helps.index')
      ->with('success', __('default.Log updated successfully.'));

  }

  public function togglePublished(Request $request, Help $help)
  {
    $validated = $request->validate([
      'is_published' => 'required'
    ]);

    $is_published = $request->input('is_published') === 'true';
    $published_at = ($is_published) ? now() : null;
    $help->update([
      'is_published' => $is_published,
      'published_at' => $published_at
    ]);

    return json_encode(array('time' => ($is_published) ? $published_at->format('Y-m-d H:i') : null));
  }

  public function destroy(Help $help)
  {
    $help->delete();
    return redirect()->route('helps.index')
      ->with('success', __('Help article deleted successfully.'));
  }

}
