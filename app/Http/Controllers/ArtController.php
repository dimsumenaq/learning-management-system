<?php

namespace App\Http\Controllers;

use App\Models\Art;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArtController extends Controller
{
    public function index(Request $request)
    {
        $arts = Art::with('comments', 'user')->latest()->paginate(5);
        $arts_count = Art::count();

        foreach ($arts as $art) {
            $art->is_liked = $art->likes()->where('user_id', auth()->user()->id)->exists();
            $created_at = Carbon::parse($art->created_at)->locale('id');
            $art->diff = $created_at->diffForHumans();
        }

        return view('content.general.dashboard', compact('arts', 'arts_count'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function artApi()
    {
        $arts = Art::with('comments', 'user')->latest()->paginate(5);

        foreach ($arts as $art) {
            $art->is_liked = $art->likes()->where('user_id', auth()->user()->id)->exists();
            $created_at = Carbon::parse($art->created_at)->locale('id');
            $art->diff = $created_at->diffForHumans();
        }

        return response()->json($arts, 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'required|string|max:5000',
            'file' => 'required|mimes:png,jpg,jpeg,mp4,mkv|max:50000',
            'category' => 'required',
        ]);

        $request->merge([
            'user_id' => auth()->user()->id,
        ]);

        $file = $request->file('file');
        $fileName = time() . '.' . $request->file->extension();
        $file->storeAs('uploads', $fileName, 'public');
        $path = '/storage/uploads/' . $fileName;

        $input = $request->all();

        $input['file'] = $path;

        Art::create($input);

        return redirect()->route('dashboard')
            ->with('success', 'Berhasil mengunggah karya.');
    }

    public function update(Request $request, Art $art)
    {
        $request->validate([
            'title' => 'required',
            'caption' => 'required',
        ]);

        $art->update($request->all());

        return redirect()->route('dashboard')
            ->with('success', 'Berhasil memperbarui karya.');
    }

    public function destroy(Art $art)
    {
        if (auth()->user()->id != $art->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Kamu tidak memiliki hak untuk menghapus karya ini.');
        }

        if ($art->comments()->exists()) {
            $art->comments()->delete();
        }

        $art->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Karya berhasil dihapus.');
    }

    public function reply(Request $request, Art $art, Comment $comment)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $request->merge([
            'user_id' => auth()->user()->id,
            'art_id' => $art->id,
            'parent_id' => $comment,
        ]);

        Comment::create($request->all());

        return redirect()->route('dashboard')
            ->with('success', 'Reply posted successfully.');
    }
}
