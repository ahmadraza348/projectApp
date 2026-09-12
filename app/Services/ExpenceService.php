<?php

namespace App\Services;

use App\Models\Expence;
use Illuminate\Support\Carbon;

class ExpenceService
{
    public function fetchData()
    {
        return Expence::with(['project', 'creator', 'approver'])->latest('expense_date')->paginate(10);
    }

    public function store(array $data): Expence
    {
        $data['created_by'] = auth()->id();
        $this->setApprovalFields($data);
        return Expence::create($data);
    }

    public function update(Expence $expence, array $data): Expence
    {
        $this->setApprovalFields($data);
        $expence->update($data);
        return $expence;
    }

    public function destroy(Expence $expence): void
    {
        $expence->delete();
    }

    private function setApprovalFields(array &$data): void
    {
        if (($data['status'] ?? null) === 'approved') {
            $data['approved_by'] = auth()->id();
            $data['approved_at'] = $data['approved_at'] ?? Carbon::now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
