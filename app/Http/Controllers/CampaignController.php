<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CampaignController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('visualizar_financeiro');

        $query = Campaign::with('transactions');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $campaigns = $query->latest()->paginate(10);

        return view('campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $this->authorize('gerenciar_campanhas');

        return view('campaigns.create');
    }

    public function store(StoreCampaignRequest $request): RedirectResponse
    {
        $this->authorize('gerenciar_campanhas');

        Campaign::create($request->validated());
        return redirect()->route('financial.campaigns.index')->with('success', 'Campanha criada com sucesso.');
    }

    public function show(Campaign $campaign): View
    {
        $this->authorize('visualizar_financeiro');

        $campaign->load('transactions.subcategory.financialCategory');
        return view('campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign): View
    {
        $this->authorize('gerenciar_campanhas');

        return view('campaigns.edit', compact('campaign'));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): RedirectResponse
    {
        $this->authorize('gerenciar_campanhas');

        $campaign->update($request->validated());
        return redirect()->route('financial.campaigns.index')->with('success', 'Campanha atualizada com sucesso.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $this->authorize('gerenciar_campanhas');

        if ($campaign->transactions()->exists()) {
            return redirect()->route('financial.campaigns.index')
                ->with('error', 'Não é possível excluir uma campanha que possui transações.');
        }

        $campaign->delete();
        return redirect()->route('financial.campaigns.index')->with('success', 'Campanha excluída com sucesso.');
    }
}
