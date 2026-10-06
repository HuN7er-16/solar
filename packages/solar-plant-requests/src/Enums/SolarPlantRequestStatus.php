<?php

namespace SolarPlantRequests\Enums;

enum SolarPlantRequestStatus: string
{
    case INITIAL                 = 'initial_registration';
    case UNDER_REVIEW            = 'under_review';
    case AWAITING_USER_APPROVAL  = 'awaiting_user_approval';
    case PACKAGE_SELECTION       = 'package_selection';
    case CONTRACTOR_SELECTION    = 'contractor_selection';
    case CONTRACTOR_ASSIGNED     = 'contractor_assigned';
    case EQUIPMENT_INSTALLATION  = 'equipment_installation';
    case INSPECTION              = 'inspection';
    case CERTIFICATE_ISSUED      = 'certificate_issued';

    public function label(): string
    {
        return match ($this) {
            self::INITIAL                => 'ثبت اولیه',
            self::UNDER_REVIEW           => 'بررسی کارشناسی',
            self::AWAITING_USER_APPROVAL => 'تایید گزارش کارشناسی',
            self::PACKAGE_SELECTION      => 'انتخاب پکیج',
            self::CONTRACTOR_SELECTION   => 'انتخاب پیمانکار',
            self::CONTRACTOR_ASSIGNED    => 'آماده تعریف پروژه',
            self::EQUIPMENT_INSTALLATION => 'نصب تجهیزات',
            self::INSPECTION             => 'بازرسی و ثبت نتیجه',
            self::CERTIFICATE_ISSUED     => 'صدور گواهی',
        };
    }

    public static function labels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->all();
    }

    public function next(): ?self
    {
        return match ($this) {
            self::INITIAL                => self::UNDER_REVIEW,
            self::UNDER_REVIEW           => self::AWAITING_USER_APPROVAL,
            self::AWAITING_USER_APPROVAL => self::PACKAGE_SELECTION,
            self::PACKAGE_SELECTION      => self::CONTRACTOR_SELECTION,
            self::CONTRACTOR_SELECTION   => self::CONTRACTOR_ASSIGNED,
            self::CONTRACTOR_ASSIGNED    => self::EQUIPMENT_INSTALLATION,
            self::EQUIPMENT_INSTALLATION => self::INSPECTION,
            self::INSPECTION             => self::CERTIFICATE_ISSUED,
            self::CERTIFICATE_ISSUED     => null,
        };
    }

    /**
     * رنگ badge برای هر وضعیت
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::INITIAL                => 'bg-gray-100 text-gray-700',
            self::UNDER_REVIEW           => 'bg-blue-100 text-blue-700',
            self::AWAITING_USER_APPROVAL => 'bg-indigo-100 text-indigo-700',
            self::PACKAGE_SELECTION      => 'bg-violet-100 text-violet-700',
            self::CONTRACTOR_SELECTION   => 'bg-purple-100 text-purple-700',
            self::CONTRACTOR_ASSIGNED    => 'bg-fuchsia-100 text-fuchsia-700',
            self::EQUIPMENT_INSTALLATION => 'bg-orange-100 text-orange-700',
            self::INSPECTION             => 'bg-yellow-100 text-yellow-700',
            self::CERTIFICATE_ISSUED     => 'bg-green-100 text-green-700',
        };
    }

    /**
     * آیا متقاضی در این وضعیت نیاز به اقدام دارد؟
     */
    public function requiresUserAction(): bool
    {
        return in_array($this, [
            self::AWAITING_USER_APPROVAL,
            self::PACKAGE_SELECTION,
            self::CONTRACTOR_SELECTION,
        ]);
    }
}
