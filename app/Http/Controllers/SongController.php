<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Tag;
use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Song::with('tags');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('key', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tag')) {
            $tagId = $request->input('tag');
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        }

        $songs = $query->latest()->paginate(10);
        $tags = Tag::orderBy('name')->get();

        return view('songs.index', compact('songs', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        return view('songs.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSongRequest $request)
    {
        $song = Song::create($request->validated());

        if ($request->filled('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
            $song->tags()->sync($tagIds);
        }

        return redirect()->route('songs.index')
            ->with('success', 'Música cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Song $song)
    {
        $song->load('tags');
        return view('songs.show', compact('song'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Song $song)
    {
        $song->load('tags');
        $tags = Tag::orderBy('name')->get();
        return view('songs.edit', compact('song', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSongRequest $request, Song $song)
    {
        $song->update($request->validated());

        if ($request->filled('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
            $song->tags()->sync($tagIds);
        } else {
            $song->tags()->detach();
        }

        return redirect()->route('songs.index')
            ->with('success', 'Música atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        $song->delete();

        return redirect()->route('songs.index')
            ->with('success', 'Música excluída com sucesso.');
    }
}
