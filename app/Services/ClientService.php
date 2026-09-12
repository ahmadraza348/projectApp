<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function fetchData()
    {
        return Client::with('creator')->latest()->paginate(10);
    }

    public function store(array $data): Client
    {
        $data['created_by'] = auth()->id();
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client;
    }

    public function destroy(Client $client): void
    {
        $client->delete();
    }
}
