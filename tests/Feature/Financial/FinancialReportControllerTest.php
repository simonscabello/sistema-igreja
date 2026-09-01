<?php

use App\Models\FinancialCategory;
use App\Models\FinancialSubcategory;
use App\Models\FinancialTransaction;

beforeEach(function () {
    $this->user = userWithPermissions(['visualizar_financeiro']);

    $category = FinancialCategory::create([
        'name' => 'Dízimos',
        'active' => true,
    ]);

    $this->subcategory = FinancialSubcategory::create([
        'financial_category_id' => $category->id,
        'name' => 'Dízimo',
        'active' => true,
    ]);

    FinancialTransaction::create([
        'financial_subcategory_id' => $this->subcategory->id,
        'type' => 'entrada',
        'amount' => 100.50,
        'action_date' => '2024-07-15',
        'description' => 'Dízimo de teste',
    ]);
});

function inertiaVisit(): array
{
    $manifest = public_path('build/manifest.json');
    $version = file_exists($manifest) ? hash_file('xxh128', $manifest) : '';

    return [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
        'X-Requested-With' => 'XMLHttpRequest',
        'Accept' => 'text/html, application/xhtml+xml',
    ];
}

it('renderiza o hub de relatórios', function () {
    $this->actingAs($this->user)
        ->get(route('financial.reports.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Financial/Reports/Index'));
});

it('renderiza o balancete mensal em visita Inertia', function () {
    $this->actingAs($this->user)
        ->withHeaders(inertiaVisit())
        ->get(route('financial.reports.monthly', ['month' => 7, 'year' => 2024]))
        ->assertOk()
        ->assertJsonPath('component', 'Financial/Reports/Monthly')
        ->assertJsonPath('props.report.periodo.mes', 7)
        ->assertJsonPath('props.report.periodo.ano', 2024)
        ->assertJsonPath('props.report.total_entradas', 100.5);
});

it('renderiza o relatório anual detalhado em visita Inertia', function () {
    $this->actingAs($this->user)
        ->withHeaders(inertiaVisit())
        ->get(route('financial.reports.annual.detailed', ['year' => 2024]))
        ->assertOk()
        ->assertJsonPath('component', 'Financial/Reports/AnnualDetailed');
});

it('renderiza o relatório anual resumido em visita Inertia', function () {
    $this->actingAs($this->user)
        ->withHeaders(inertiaVisit())
        ->get(route('financial.reports.annual.summary', ['year' => 2024]))
        ->assertOk()
        ->assertJsonPath('component', 'Financial/Reports/AnnualSummary');
});

it('devolve JSON do balancete quando a request pede JSON sem Inertia', function () {
    $this->actingAs($this->user)
        ->getJson(route('financial.reports.monthly', ['month' => 7, 'year' => 2024]))
        ->assertOk()
        ->assertJsonPath('total_entradas', 100.5)
        ->assertJsonPath('periodo.mes', 7)
        ->assertJsonMissingPath('component');
});
