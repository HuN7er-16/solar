<?php

namespace ContractorCatalog\Http\Controllers;

use App\Models\User;
use ContractorCatalog\Models\Contractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ContractorRegistrationController
{
    public function showForm(): View
    {
        $provinces = Contractor::getProvinces();

        return view('contractor-catalog::contractors.register', compact('provinces'));
    }

    public function register(Request $request): RedirectResponse
    {
        // تبدیل تاریخ‌های جلالی به میلادی
        $dateFields = ['license_issue_date', 'license_expiry_date'];
        foreach ($dateFields as $field) {
            if ($request->filled($field)) {
                $request->merge([$field => toGregorianDate($request->input($field))]);
            }
        }

        $validated = $request->validate([
            // اطلاعات حساب کاربری
            'name'                      => ['required', 'string', 'max:255'],
            'phone'                     => ['required', 'string', 'size:11', 'unique:users,phone'],
            // اطلاعات شرکت
            'company_name'              => ['required', 'string', 'max:255'],
            'national_id'               => ['required', 'string', 'size:11', 'unique:contractors,national_id'],
            // اطلاعات مدیر عامل
            'ceo_name'                  => ['required', 'string', 'max:255'],
            'ceo_national_code'         => ['required', 'string', 'size:10'],
            'ceo_mobile'                => ['required', 'string', 'size:11'],
            // شخص رابط
            'contact_person_name'       => ['required', 'string', 'max:255'],
            'contact_person_mobile'     => ['required', 'string', 'size:11'],
            // اطلاعات تماس شرکت
            'company_phone'             => ['nullable', 'string', 'max:11'],
            // موقعیت
            'province'                  => ['required', 'string', 'max:100'],
            'city'                      => ['required', 'string', 'max:100'],
            'address'                   => ['required', 'string'],
            // پروانه کسب
            'license_number'            => ['required', 'string', 'max:100', 'unique:contractors,license_number'],
            'license_issue_date'        => ['required', 'date'],
            'license_expiry_date'       => ['required', 'date', 'after:license_issue_date'],
            'registered_projects_count' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required'                  => 'نام و نام خانوادگی الزامی است.',
            'phone.required'                 => 'شماره موبایل الزامی است.',
            'phone.size'                     => 'شماره موبایل باید ۱۱ رقم باشد.',
            'phone.unique'                   => 'این شماره موبایل قبلاً ثبت شده است.',
            'company_name.required'          => 'نام شرکت الزامی است.',
            'national_id.required'           => 'شناسه ملی شرکت الزامی است.',
            'national_id.size'               => 'شناسه ملی باید ۱۱ رقم باشد.',
            'national_id.unique'             => 'این شناسه ملی قبلاً ثبت شده است.',
            'ceo_name.required'              => 'نام مدیر عامل الزامی است.',
            'ceo_national_code.required'     => 'کد ملی مدیر عامل الزامی است.',
            'ceo_national_code.size'         => 'کد ملی باید ۱۰ رقم باشد.',
            'ceo_mobile.required'            => 'شماره موبایل مدیر عامل الزامی است.',
            'ceo_mobile.size'                => 'شماره موبایل باید ۱۱ رقم باشد.',
            'contact_person_name.required'   => 'نام شخص رابط الزامی است.',
            'contact_person_mobile.required' => 'شماره موبایل شخص رابط الزامی است.',
            'contact_person_mobile.size'     => 'شماره موبایل باید ۱۱ رقم باشد.',
            'province.required'              => 'استان الزامی است.',
            'city.required'                  => 'شهر الزامی است.',
            'address.required'               => 'آدرس الزامی است.',
            'license_number.required'        => 'شماره پروانه کسب الزامی است.',
            'license_number.unique'          => 'این شماره پروانه کسب قبلاً ثبت شده است.',
            'license_issue_date.required'    => 'تاریخ صدور پروانه الزامی است.',
            'license_expiry_date.required'   => 'تاریخ انقضای پروانه الزامی است.',
            'license_expiry_date.after'      => 'تاریخ انقضا باید بعد از تاریخ صدور باشد.',
        ]);

        DB::transaction(function () use ($validated) {
            // ساخت یوزر با نقش پیمانکار (role_id=2)
            $user = User::query()->create([
                'name'     => $validated['name'],
                'phone'    => $validated['phone'],
                'email'    => $validated['phone'] . '@contractor.local',
                'password' => Hash::make(Str::random(16)), // پسورد تصادفی - لاگین با OTP
                'role_id'  => 2,
            ]);

            // ساخت پروفایل پیمانکار
            Contractor::query()->create([
                'user_id'                   => $user->id,
                'company_name'              => $validated['company_name'],
                'national_id'               => $validated['national_id'],
                'ceo_name'                  => $validated['ceo_name'],
                'ceo_national_code'         => $validated['ceo_national_code'],
                'ceo_mobile'                => $validated['ceo_mobile'],
                'contact_person_name'       => $validated['contact_person_name'],
                'contact_person_mobile'     => $validated['contact_person_mobile'],
                'company_phone'             => $validated['company_phone'] ?? null,
                'province'                  => $validated['province'],
                'city'                      => $validated['city'],
                'address'                   => $validated['address'],
                'license_number'            => $validated['license_number'],
                'license_issue_date'        => $validated['license_issue_date'],
                'license_expiry_date'       => $validated['license_expiry_date'],
                'registered_projects_count' => $validated['registered_projects_count'] ?? 0,
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'ثبت‌نام با موفقیت انجام شد. اکنون می‌توانید با شماره موبایل خود وارد شوید.');
    }
}
