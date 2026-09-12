<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\ClientService;

class ClientController extends Controller
{
    public function __construct(private ClientService $service) {}

    public function index()
    {
        $clients = $this->service->fetchData();
        return view('clients.index', compact('clients'));
    }

    public function store(ClientRequest $request)
    {
        $this->service->store($request->validated());
        return back()->with('success', 'Client Added');
    }

    public function update(ClientRequest $request, Client $client)
    {
        $this->service->update($client, $request->validated());
        return back()->with('success', 'Client Updated');
    }

    public function destroy(Client $client)
    {
        $this->service->destroy($client);
        return back()->with('success', 'Client Deleted');
    }
}
