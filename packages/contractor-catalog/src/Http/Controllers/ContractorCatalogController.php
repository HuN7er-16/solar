<?php

namespace ContractorCatalog\Http\Controllers;

use App\Models\User;
use ContractorCatalog\Models\Contractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractorCatalogController
{
    public function index(): View
    {
        $contractors = Contractor::query()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('contractor-catalog::contractors.index', compact('contractors'));
    }

    public function create(): View
    {
        $provinces = Contractor::getProvinces();

        // کاربرانی که هنوز پروفایل پیمانکار ندارند
        $users = User::query()
            ->whereNotIn('id', Contractor::query()->pluck('user_id')->filter())
            ->orderBy('name')
            ->get();

        return view('contractor-catalog::contractors.create', compact('provinces', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $dateFields = ['license_issue_date', 'license_expiry_date'];
        foreach ($dateFields as $field) {
            if ($request->has($field) && !empty($request->input($field))) {
                $request->merge([$field => toGregorianDate($request->input($field))]);
            }
        }

        $validated = $request->validate([
            'user_id'                   => ['required', 'exists:users,id', 'unique:contractors,user_id'],
            'company_name'              => ['required', 'string', 'max:255'],
            'national_id'               => ['required', 'string', 'size:11', 'unique:contractors,national_id'],
            'ceo_name'                  => ['required', 'string', 'max:255'],
            'ceo_national_code'         => ['required', 'string', 'size:10'],
            'ceo_mobile'                => ['required', 'string', 'size:11'],
            'contact_person_name'       => ['required', 'string', 'max:255'],
            'contact_person_mobile'     => ['required', 'string', 'size:11'],
            'company_phone'             => ['nullable', 'string', 'max:11'],
            'province'                  => ['required', 'string', 'max:100'],
            'city'                      => ['required', 'string', 'max:100'],
            'address'                   => ['required', 'string'],
            'license_number'            => ['required', 'string', 'max:100', 'unique:contractors,license_number'],
            'license_issue_date'        => ['required', 'date'],
            'license_expiry_date'       => ['required', 'date', 'after:license_issue_date'],
            'registered_projects_count' => ['nullable', 'integer', 'min:0'],
        ], [
            'user_id.required'               => 'انتخاب کاربر الزامی است.',
            'user_id.exists'                 => 'کاربر انتخاب‌شده وجود ندارد.',
            'user_id.unique'                 => 'این کاربر قبلاً به عنوان پیمانکار ثبت شده است.',
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

        $validated['registered_projects_count'] = $validated['registered_projects_count'] ?? 0;

        DB::transaction(function () use ($validated) {
            // تنظیم نقش پیمانکار (id=2) روی کاربر انتخاب‌شده
            User::query()
                ->where('id', $validated['user_id'])
                ->update(['role_id' => 2]);

            Contractor::query()->create($validated);
        });

        return redirect()
            ->route('contractor-catalog.index')
            ->with('success', 'پیمانکار جدید با موفقیت ثبت شد.');
    }

    public function show(Contractor $contractor): View
    {
        $contractor->load('user');

        return view('contractor-catalog::contractors.show', compact('contractor'));
    }

    public function edit(Contractor $contractor): View
    {
        $contractor->load('user');
        $provinces = Contractor::getProvinces();

        // کاربرانی که پیمانکار نیستند + کاربر فعلی این پیمانکار
        $users = User::query()
            ->where(function ($q) use ($contractor) {
                $q->whereNotIn('id', Contractor::query()->pluck('user_id')->filter())
                  ->orWhere('id', $contractor->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('contractor-catalog::contractors.edit', compact('contractor', 'provinces', 'users'));
    }

    public function update(Request $request, Contractor $contractor): RedirectResponse
    {
        $dateFields = ['license_issue_date', 'license_expiry_date'];
        foreach ($dateFields as $field) {
            if ($request->has($field) && !empty($request->input($field))) {
                $request->merge([$field => toGregorianDate($request->input($field))]);
            }
        }

        $validated = $request->validate([
            'user_id'                   => ['required', 'exists:users,id', 'unique:contractors,user_id,' . $contractor->id],
            'company_name'              => ['required', 'string', 'max:255'],
            'national_id'               => ['required', 'string', 'size:11', 'unique:contractors,national_id,' . $contractor->id],
            'ceo_name'                  => ['required', 'string', 'max:255'],
            'ceo_national_code'         => ['required', 'string', 'size:10'],
            'ceo_mobile'                => ['required', 'string', 'size:11'],
            'contact_person_name'       => ['required', 'string', 'max:255'],
            'contact_person_mobile'     => ['required', 'string', 'size:11'],
            'company_phone'             => ['nullable', 'string', 'max:11'],
            'province'                  => ['required', 'string', 'max:100'],
            'city'                      => ['required', 'string', 'max:100'],
            'address'                   => ['required', 'string'],
            'license_number'            => ['required', 'string', 'max:100', 'unique:contractors,license_number,' . $contractor->id],
            'license_issue_date'        => ['required', 'date'],
            'license_expiry_date'       => ['required', 'date', 'after:license_issue_date'],
            'registered_projects_count' => ['nullable', 'integer', 'min:0'],
        ], [
            'user_id.required'          => 'انتخاب کاربر الزامی است.',
            'user_id.exists'            => 'کاربر انتخاب‌شده وجود ندارد.',
            'user_id.unique'            => 'این کاربر قبلاً به عنوان پیمانکار ثبت شده است.',
            'national_id.size'          => 'شناسه ملی باید ۱۱ رقم باشد.',
            'national_id.unique'        => 'این شناسه ملی قبلاً ثبت شده است.',
            'ceo_national_code.size'    => 'کد ملی باید ۱۰ رقم باشد.',
            'ceo_mobile.size'           => 'شماره موبایل باید ۱۱ رقم باشد.',
            'contact_person_mobile.size'=> 'شماره موبایل باید ۱۱ رقم باشد.',
            'license_number.unique'     => 'این شماره پروانه کسب قبلاً ثبت شده است.',
            'license_expiry_date.after' => 'تاریخ انقضا باید بعد از تاریخ صدور باشد.',
        ]);

        DB::transaction(function () use ($validated, $contractor) {
            // اگر کاربر عوض شد، نقش پیمانکار را روی کاربر جدید تنظیم کن
            if ((string) $validated['user_id'] !== (string) $contractor->user_id) {
                User::query()
                    ->where('id', $validated['user_id'])
                    ->update(['role_id' => 2]);
            }

            $contractor->update($validated);
        });

        return redirect()
            ->route('contractor-catalog.index')
            ->with('success', 'اطلاعات پیمانکار با موفقیت ویرایش شد.');
    }

    public function destroy(Contractor $contractor): RedirectResponse
    {
        // فقط پروفایل پیمانکار حذف می‌شود، حساب کاربری دست‌نخورده می‌ماند
        $contractor->delete();

        return redirect()
            ->route('contractor-catalog.index')
            ->with('success', 'پروفایل پیمانکار با موفقیت حذف شد.');
    }

    public function lastRecord(): JsonResponse
    {
        $contractor = Contractor::query()->latest()->first();

        if ($contractor) {
            $arr = $contractor->toArray();
            if (!empty($arr['license_issue_date'])) {
                $arr['license_issue_date'] = toJalaliFormatted($contractor->license_issue_date, 'Y/m/d');
            }
            if (!empty($arr['license_expiry_date'])) {
                $arr['license_expiry_date'] = toJalaliFormatted($contractor->license_expiry_date, 'Y/m/d');
            }
            return response()->json($arr);
        }

        return response()->json($contractor);
    }
}
