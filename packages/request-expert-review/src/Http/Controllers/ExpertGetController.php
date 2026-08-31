<?php

namespace RequestExpertReview\Http\Controllers;

use App\Models\User;

class ExpertGetController
{
    /**
     * دریافت تمام کاربران با نقش کارشناس
     * TODO: مقدار expert را در config/request-expert-review.php پر کنید
     */
    public static function getAll()
    {
        $expertRoleId = config('request-expert-review.roles.expert'); // <-- null تا زمانی که پر نشده

        if (! $expertRoleId) {
            return collect();
        }

        return User::query()
            ->where('role_id', $expertRoleId)
            ->orderBy('name')
            ->get();
    }

    public static function getById(int $id): ?User
    {
        return User::query()->find($id);
    }
}
