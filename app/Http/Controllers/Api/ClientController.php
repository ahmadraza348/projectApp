<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    use ApiResponse;

    public function __construct(private ClientService $service) {}

    public function index(): JsonResponse
    {
        return $this->successResponse(ClientResource::collection($this->service->fetchData())->response()->getData(true), 'Clients fetched successfully');
    }

    public function store(ClientRequest $request): JsonResponse
    {
        return $this->successResponse(new ClientResource($this->service->store($request->validated())), 'Client created successfully', 201);
    }

    public function show(Client $client): JsonResponse
    {
        return $this->successResponse(new ClientResource($client));
    }

    public function update(ClientRequest $request, Client $client): JsonResponse
    {
        return $this->successResponse(new ClientResource($this->service->update($client, $request->validated())), 'Client updated successfully');
    }

    public function destroy(Client $client): JsonResponse
    {
        $this->service->destroy($client);
        return $this->successResponse(null, 'Client deleted successfully');
    }
}
