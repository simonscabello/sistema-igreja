<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Models\Song;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SongController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('visualizar_musicas');

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

        return Inertia::render('Songs/Index', compact('songs', 'tags'));
    }

    public function create(): Response
    {
        $this->authorize('gerenciar_musicas');

        $tags = Tag::orderBy('name')->get();

        return Inertia::render('Songs/Create', compact('tags'));
    }

    public function store(StoreSongRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_musicas');

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

    public function show(Song $song): Response
    {
        $this->authorize('visualizar_musicas');

        $song->load('tags');

        return Inertia::render('Songs/Show', compact('song'));
    }

    public function edit(Song $song): Response
    {
        $this->authorize('gerenciar_musicas');

        $song->load('tags');
        $tags = Tag::orderBy('name')->get();

        return Inertia::render('Songs/Edit', compact('song', 'tags'));
    }

    public function update(UpdateSongRequest $request, Song $song): RedirectResponse
    {
        $this->authorize('gerenciar_musicas');

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
        $this->authorize('gerenciar_musicas');

        $song->delete();

        return redirect()->route('songs.index')
            ->with('success', 'Música excluída com sucesso.');
    }
}
