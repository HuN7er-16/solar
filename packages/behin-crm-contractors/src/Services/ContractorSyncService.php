<?php

namespace BehinCrmContractors\Services;

use App\Models\User;
use Behin\CrmClient\CrmClient;
use BehinCrmContractors\Models\CrmContractor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class ContractorSyncService
{
    /**
     * نوع فعالیت واجد شرایط برای sync
     */
    public const ELIGIBLE_ROW_TYPE = 'نصب و تعمیر پنل‌های خورشیدی';

    public function __construct(
        private readonly CrmClient $crmClient
    ) {}

    /**
     * اجرای کامل فرآیند همگام‌سازی پیمانکاران از CRM
     *
     * @return array{created: int, updated: int, skipped: int, errors: array<string>}
     * @throws RuntimeException اگر CrmClient تنظیم نشده باشد یا role پیمانکار یافت نشود
     */
    public function sync(): array
    {
        $result = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors'  => [],
        ];

        // بررسی تنظیمات CRM — اگر نباشد RuntimeException پرتاب می‌شود
        $this->crmClient->ensureConfigured();

        // لود نقش پیمانکار از behin_roles
        $role = DB::table('behin_roles')->where('name', 'پیمانکار')->first();

        if (! $role) {
            throw new RuntimeException('نقش «پیمانکار» در جدول behin_roles یافت نشد.');
        }

        $roleId = (int) $role->id;

        // دریافت لیست مراکز از CRM
        $response = $this->crmClient->request('rhs_servicecenters');

        if (! $response->successful()) {
            $result['errors'][] = 'خطا در دریافت مراکز از CRM — HTTP status: ' . $response->status();

            return $result;
        }

        $centers = $response->json()['value'] ?? [];

        // فیلتر مراکز واجد شرایط (rhs_row === نوع فعالیت مجاز)
        foreach ($centers as $center) {
            if (($center['rhs_row'] ?? '') !== self::ELIGIBLE_ROW_TYPE) {
                continue;
            }

            $this->syncCenter($center, $roleId, $result);
        }

        return $result;
    }

    /**
     * پردازش یک مرکز واجد شرایط: upsert کاربر و crm_contractor
     *
     * @param array<string, mixed>                                          $center
     * @param int                                                           $roleId
     * @param array{created: int, updated: int, skipped: int, errors: array<string>} $result
     */
    private function syncCenter(array $center, int $roleId, array &$result): void
    {
        $mobile = trim((string) ($center['rhs_mobile'] ?? ''));

        // اگر شماره موبایل خالی بود، مرکز را رد کن
        if ($mobile === '') {
            $centerName = $center['rhs_name'] ?? ($center['rhs_servicecenterid'] ?? 'نامشخص');
            $result['skipped']++;
            $result['errors'][] = "مرکز «{$centerName}» به دلیل خالی بودن rhs_mobile رد شد.";

            return;
        }

        $centerId = $center['rhs_servicecenterid'] ?? null;
        $fullName = trim(($center['rhs_fullname'] ?? '') . ' ' . ($center['rhs_lastname'] ?? ''));

        // تعیین اینکه آیا کاربر از قبل وجود دارد (برای تصمیم درباره password)
        $userExists = User::where('email', $mobile)->exists();

        // داده‌های مشترک برای ایجاد و بروزرسانی
        $userData = [
            'name'    => $fullName,
            'phone'   => $mobile,
            'role_id' => $roleId,
        ];

        // password فقط در ایجاد اولیه تنظیم می‌شود
        if (! $userExists) {
            $userData['password'] = Hash::make($mobile);
        }

        $user = User::updateOrCreate(
            ['email' => $mobile],
            $userData
        );

        // upsert رکورد crm_contractors
        $contractorExists = CrmContractor::where('crm_service_center_id', $centerId)->exists();

        CrmContractor::updateOrCreate(
            ['crm_service_center_id' => $centerId],
            [
                'user_id'     => $user->id,
                'center_name' => $center['rhs_name'] ?? '',
                'mobile'      => $mobile,
                'province'    => $center['rhs_province'] ?? null,
                'synced_at'   => now(),
            ]
        );

        if ($contractorExists) {
            $result['updated']++;
        } else {
            $result['created']++;
        }
    }
}
