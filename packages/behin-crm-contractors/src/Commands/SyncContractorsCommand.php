<?php

namespace BehinCrmContractors\Commands;

use BehinCrmContractors\Services\ContractorSyncService;
use Illuminate\Console\Command;
use RuntimeException;

class SyncContractorsCommand extends Command
{
    protected $signature = 'crm:sync-contractors';
    protected $description = 'همگام‌سازی پیمانکاران از CRM به پایگاه داده';

    public function handle(ContractorSyncService $syncService): int
    {
        try {
            $result = $syncService->sync();

            $this->info("همگام‌سازی با موفقیت انجام شد:");
            $this->line("  ایجاد شده: {$result['created']}");
            $this->line("  بروزرسانی شده: {$result['updated']}");
            $this->line("  رد شده: {$result['skipped']}");

            if (!empty($result['errors'])) {
                $this->warn("خطاها:");
                foreach ($result['errors'] as $error) {
                    $this->warn("  - $error");
                }
            }

            return self::SUCCESS;
        } catch (RuntimeException $e) {
            $this->error("خطا در همگام‌سازی: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
