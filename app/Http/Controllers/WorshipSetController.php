<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorshipSetRequest;
use App\Http\Requests\UpdateWorshipSetRequest;
use App\Models\Member;
use App\Models\Song;
use App\Models\WorshipFunction;
use App\Models\WorshipSet;
use App\Models\WorshipSetAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorshipSetController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('visualizar_escalas_louvor');

        $query = WorshipSet::with([
            'songs',
            'assignments.worshipFunction',
            'assignments.member',
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('assignments.member', function ($memberQuery) use ($search) {
                    $memberQuery->where('full_name', 'like', "%{$search}%");
                });
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

        return Inertia::render('WorshipSets/Index', compact('worshipSets'));
    }

    public function create(): Response
    {
        $this->authorize('gerenciar_escalas_louvor');

        $songs = Song::orderBy('name')->get();
        $functions = $this->functionsForSchedule();
        $clonedSet = null;

        return Inertia::render('WorshipSets/Create', compact('songs', 'functions', 'clonedSet'));
    }

    public function store(StoreWorshipSetRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipSet = WorshipSet::create($request->safe()->except(['songs', 'song_keys', 'assignments']));

        $this->syncSongs($worshipSet, $request);
        $this->syncAssignments($worshipSet, $request->input('assignments', []));

        return redirect()->route('worship-sets.index')
            ->with('success', 'Repertório cadastrado com sucesso.');
    }

    public function show(WorshipSet $worshipSet): Response
    {
        $this->authorize('visualizar_escalas_louvor');

        $worshipSet->load([
            'songs',
            'assignments' => fn ($query) => $query->orderBy('order'),
            'assignments.worshipFunction',
            'assignments.member',
        ]);

        return Inertia::render('WorshipSets/Show', compact('worshipSet'));
    }

    public function edit(WorshipSet $worshipSet): Response
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipSet->load([
            'songs',
            'assignments' => fn ($query) => $query->orderBy('order'),
            'assignments.worshipFunction',
            'assignments.member',
        ]);
        $songs = Song::orderBy('name')->get();
        $functions = $this->functionsForSchedule($worshipSet);

        return Inertia::render('WorshipSets/Edit', compact('worshipSet', 'songs', 'functions'));
    }

    public function update(UpdateWorshipSetRequest $request, WorshipSet $worshipSet): RedirectResponse
    {
        $this->authorize('gerenciar_escalas_louvor');

        $worshipSet->update($request->safe()->except(['songs', 'song_keys', 'assignments']));

        $this->syncSongs($worshipSet, $request);
        $this->syncAssignments($worshipSet, $request->input('assignments', []));

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

    public function clone(WorshipSet $worshipSet): Response
    {
        $this->authorize('gerenciar_escalas_louvor');

        $songs = Song::orderBy('name')->get();
        $functions = $this->functionsForSchedule();
        $clonedSet = $worshipSet->replicate();
        $clonedSet->date = now()->addDay();
        $clonedSet->order_notes = '';
        $clonedSet->observations = '';

        $clonedSet->songs = $worshipSet->songs;

        return Inertia::render('WorshipSets/Create', compact('songs', 'functions', 'clonedSet'));
    }

    private function syncSongs(WorshipSet $worshipSet, StoreWorshipSetRequest|UpdateWorshipSetRequest $request): void
    {
        if ($request->filled('songs')) {
            $songData = [];
            foreach ($request->songs as $index => $songId) {
                $songData[$songId] = [
                    'order' => $index + 1,
                    'key_used' => $request->input("song_keys.{$songId}", null),
                ];
            }
            $worshipSet->songs()->sync($songData);

            return;
        }

        $worshipSet->songs()->detach();
    }

    private function syncAssignments(WorshipSet $worshipSet, ?array $assignments): void
    {
        $worshipSet->assignments()->delete();

        if (! is_array($assignments) || $assignments === []) {
            return;
        }

        foreach ($assignments as $functionId => $memberIds) {
            if (! is_array($memberIds)) {
                continue;
            }

            foreach (array_values($memberIds) as $index => $memberId) {
                WorshipSetAssignment::create([
                    'worship_set_id' => $worshipSet->id,
                    'worship_function_id' => $functionId,
                    'member_id' => $memberId,
                    'order' => $index + 1,
                ]);
            }
        }
    }

    private function functionsForSchedule(?WorshipSet $worshipSet = null): array
    {
        $usedFunctionIds = [];

        if ($worshipSet) {
            $usedFunctionIds = $worshipSet->assignments
                ->pluck('worship_function_id')
                ->unique()
                ->values()
                ->all();
        }

        $functions = WorshipFunction::query()
            ->where(function ($query) use ($usedFunctionIds) {
                $query->where('is_active', true);

                if ($usedFunctionIds !== []) {
                    $query->orWhereIn('id', $usedFunctionIds);
                }
            })
            ->with(['members' => function ($query) {
                $query->with('foto')->orderBy('full_name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $functions->map(function (WorshipFunction $function) {
            return [
                'id' => $function->id,
                'name' => $function->name,
                'slug' => $function->slug,
                'members' => $function->members->map(function (Member $member) {
                    $foto = $member->foto->first();
                    $image = null;
                    if ($foto) {
                        $image = filled($foto->url) ? $foto->url : asset('storage/'.$foto->path);
                    }

                    return [
                        'id' => $member->id,
                        'name' => $member->full_name,
                        'image' => $image,
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    }
}
