<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Tag;
use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SongController extends Controller
{
    public function index(Request $request): View
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

    public function create(): View
    {
        $tags = Tag::orderBy('name')->get();

        return view('songs.create', compact('tags'));
    }

    public function store(StoreSongRequest $request): RedirectResponse
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

    public function show(Song $song): View
    {
        $song->load('tags');

        return view('songs.show', compact('song'));
    }

    public function edit(Song $song): View
    {
        $song->load('tags');
        $tags = Tag::orderBy('name')->get();

        return view('songs.edit', compact('song', 'tags'));
    }

    public function update(UpdateSongRequest $request, Song $song): RedirectResponse
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

    public function destroy(Song $song): RedirectResponse
    {
        $song->delete();

        return redirect()->route('songs.index')
            ->with('success', 'Música excluída com sucesso.');
    }
}
