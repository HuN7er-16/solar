<?php

namespace BehinCrmContractors\Http\Controllers;

use BehinCrmContractors\Models\CrmContractor;
use BehinCrmContractors\Services\ContractorSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use RuntimeException;

class CrmContractorController extends Controller
{
    public function index(): View
    {
        $contractors = CrmContractor::with('user')
            ->orderByDesc('synced_at')
            ->paginate(25);

        return view('CrmContractorsView::contractors.index', compact('contractors'));
    }

    public function triggerSync(ContractorSyncService $syncService): RedirectResponse
    {
        try {
            $result = $syncService->sync();

            $message = "همگام‌سازی با موفقیت انجام شد. "
                . "ایجاد شده: {$result['created']} | "
                . "بروزرسانی: {$result['updated']} | "
                . "رد شده: {$result['skipped']}";

            return redirect()->route('crm-contractors.index')
                ->with('success', $message);
        } catch (RuntimeException $e) {
            return redirect()->route('crm-contractors.index')
                ->with('error', 'خطا در همگام‌سازی: ' . $e->getMessage());
        }
    }
}
