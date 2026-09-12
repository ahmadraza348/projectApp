<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenceRequest;
use App\Models\Expence;
use App\Models\Project;
use App\Services\ExpenceService;

class ExpenceController extends Controller
{
    public function __construct(private ExpenceService $service) {}

    public function index()
    {
        $expences = $this->service->fetchData();
        $projects = Project::orderBy('name')->get(['id', 'name']);
        return view('expences.index', compact('expences', 'projects'));
    }

    public function store(ExpenceRequest $request)
    {
        $this->service->store($request->validated());
        return back()->with('success', 'Expense Added');
    }

    public function update(ExpenceRequest $request, Expence $expence)
    {
        $this->service->update($expence, $request->validated());
        return back()->with('success', 'Expense Updated');
    }

    public function destroy(Expence $expence)
    {
        $this->service->destroy($expence);
        return back()->with('success', 'Expense Deleted');
    }
}
