<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Campaign::with('transactions');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $campaigns = $query->latest()->paginate(10);

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request)
    {
        Campaign::create($request->validated());
        return redirect()->route('campaigns.index')->with('success', 'Campanha criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        $campaign->load('transactions.subcategory.financialCategory');
        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        $campaign->update($request->validated());
        return redirect()->route('campaigns.index')->with('success', 'Campanha atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        if ($campaign->transactions()->exists()) {
            return redirect()->route('campaigns.index')
                ->with('error', 'Não é possível excluir uma campanha que possui transações.');
        }

        $campaign->delete();
        return redirect()->route('campaigns.index')->with('success', 'Campanha excluída com sucesso.');
    }
}
