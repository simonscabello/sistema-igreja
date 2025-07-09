<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorshipSet;
use App\Models\Song;
use App\Http\Requests\StoreWorshipSetRequest;
use App\Http\Requests\UpdateWorshipSetRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WorshipSetController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('visualizar_escalas_louvor');

        $query = WorshipSet::with('songs');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('singer', 'like', "%{$search}%")
                  ->orWhere('preacher', 'like', "%{$search}%");
            });
        }

        if ($request->filled('period')) {
            $query->where('period', $request->input('period'));
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->input('date_to'));
        }

        $worshipSets = $query->latest('date')->paginate(10);

        return view('worship-sets.index', compact('worshipSets'));
    }

    public function create(): View
    {
        $this->authorize('gerenciar_escalas_louvor');

        $songs = Song::orderBy('name')->get();
        $clonedSet = null;

        return view('worship-sets.create', compact('songs', 'clonedSet'));
    }

    public function store(StoreWorshipSetRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipSet = WorshipSet::create($request->validated());

        if ($request->filled('songs')) {
            $songData = [];
            foreach ($request->songs as $index => $songId) {
                $songData[$songId] = [
                    'order' => $index + 1,
                    'key_used' => $request->input("song_keys.{$songId}", null)
                ];
            }
            $worshipSet->songs()->attach($songData);
        }

        return redirect()->route('worship-sets.index')
            ->with('success', 'Repertório cadastrado com sucesso.');
    }

    public function show(WorshipSet $worshipSet): View
    {
        $this->authorize('visualizar_escalas_louvor');

        $worshipSet->load('songs');

        return view('worship-sets.show', compact('worshipSet'));
    }

    public function edit(WorshipSet $worshipSet): View
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipSet->load('songs');
        $songs = Song::orderBy('name')->get();

        return view('worship-sets.edit', compact('worshipSet', 'songs'));
    }

    public function update(UpdateWorshipSetRequest $request, WorshipSet $worshipSet): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipSet->update($request->validated());

        if ($request->filled('songs')) {
            $songData = [];
            foreach ($request->songs as $index => $songId) {
                $songData[$songId] = [
                    'order' => $index + 1,
                    'key_used' => $request->input("song_keys.{$songId}", null)
                ];
            }
            $worshipSet->songs()->sync($songData);
        } else {
            $worshipSet->songs()->detach();
        }

        return redirect()->route('worship-sets.index')
            ->with('success', 'Repertório atualizado com sucesso.');
    }

    public function destroy(WorshipSet $worshipSet): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipSet->delete();

        return redirect()->route('worship-sets.index')
            ->with('success', 'Repertório excluído com sucesso.');
    }

    public function clone(WorshipSet $worshipSet): View
    {
        $this->authorize('gerenciar_escalas_louvor');

        $songs = Song::orderBy('name')->get();
        $clonedSet = $worshipSet->replicate();
        $clonedSet->date = now()->addDay();
        $clonedSet->singer = '';
        $clonedSet->preacher = '';
        $clonedSet->order_notes = '';
        $clonedSet->observations = '';

        $clonedSet->songs = $worshipSet->songs;

        return view('worship-sets.create', compact('songs', 'clonedSet'));
    }
}
