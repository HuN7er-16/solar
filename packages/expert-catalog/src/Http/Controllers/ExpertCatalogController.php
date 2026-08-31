<?php

namespace ExpertCatalog\Http\Controllers;

use App\Models\User;
use ExpertCatalog\Models\Expert;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpertCatalogController
{
    public function index(): View
    {
        $experts = Expert::query()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('expert-catalog::experts.index', compact('experts'));
    }

    public function create(): View
    {
        $provinces = Expert::getProvinces();

        $users = User::query()
            ->whereNotIn('id', Expert::query()->pluck('user_id'))
            ->orderBy('name')
            ->get();

        return view('expert-catalog::experts.create', compact('provinces', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'         => ['required', 'exists:users,id', 'unique:experts,user_id'],
            'expert_code'     => ['required', 'string', 'max:50', 'unique:experts,expert_code'],
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'national_id'     => ['required', 'string', 'size:10', 'unique:experts,national_id'],
            'mobile'          => ['required', 'string', 'size:11'],
            'phone'           => ['nullable', 'string', 'max:11'],
            'province'        => ['required', 'string', 'max:100'],
            'city'            => ['required', 'string', 'max:100'],
            'address'         => ['required', 'string'],
            'is_certificated' => ['nullable', 'boolean'],
        ], [
            'user_id.required'       => 'انتخاب کاربر الزامی است.',
            'user_id.exists'         => 'کاربر انتخاب شده وجود ندارد.',
            'user_id.unique'         => 'این کاربر قبلاً به عنوان کارشناس ثبت شده است.',
            'expert_code.required'   => 'کد کارشناس الزامی است.',
            'expert_code.unique'     => 'این کد کارشناس قبلاً ثبت شده است.',
            'first_name.required'    => 'نام الزامی است.',
            'last_name.required'     => 'نام خانوادگی الزامی است.',
            'national_id.required'   => 'کد ملی الزامی است.',
            'national_id.size'       => 'کد ملی باید ۱۰ رقم باشد.',
            'national_id.unique'     => 'این کد ملی قبلاً ثبت شده است.',
            'mobile.required'        => 'شماره همراه الزامی است.',
            'mobile.size'            => 'شماره همراه باید ۱۱ رقم باشد.',
            'province.required'      => 'استان الزامی است.',
            'city.required'          => 'شهر الزامی است.',
            'address.required'       => 'آدرس الزامی است.',
        ]);

        DB::transaction(function () use ($validated) {
            $expertRole = DB::table('behin_roles')->where('name', 'کارشناس')->first();
            if ($expertRole) {
                User::query()->where('id', $validated['user_id'])->update([
                    'role_id' => $expertRole->id,
                ]);
            }

            Expert::query()->create([
                'user_id'         => $validated['user_id'],
                'expert_code'     => $validated['expert_code'],
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'national_id'     => $validated['national_id'],
                'mobile'          => $validated['mobile'],
                'phone'           => $validated['phone'] ?? null,
                'province'        => $validated['province'],
                'city'            => $validated['city'],
                'address'         => $validated['address'],
                'is_certificated' => (bool) ($validated['is_certificated'] ?? false),
            ]);
        });

        return redirect()
            ->route('expert-catalog.index')
            ->with('success', 'اطلاعات کارشناس با موفقیت ثبت شد.');
    }

    public function show(Expert $expert): View
    {
        $expert->load('user');

        return view('expert-catalog::experts.show', compact('expert'));
    }

    public function edit(Expert $expert): View
    {
        $expert->load('user');
        $provinces = Expert::getProvinces();

        $users = User::query()
            ->where(function ($q) use ($expert) {
                $q->whereNotIn('id', Expert::query()->pluck('user_id'))
                  ->orWhere('id', $expert->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('expert-catalog::experts.edit', compact('expert', 'provinces', 'users'));
    }

    public function update(Request $request, Expert $expert): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'         => ['required', 'exists:users,id', 'unique:experts,user_id,' . $expert->id],
            'expert_code'     => ['required', 'string', 'max:50', 'unique:experts,expert_code,' . $expert->id],
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'national_id'     => ['required', 'string', 'size:10', 'unique:experts,national_id,' . $expert->id],
            'mobile'          => ['required', 'string', 'size:11'],
            'phone'           => ['nullable', 'string', 'max:11'],
            'province'        => ['required', 'string', 'max:100'],
            'city'            => ['required', 'string', 'max:100'],
            'address'         => ['required', 'string'],
            'is_certificated' => ['nullable', 'boolean'],
        ], [
            'user_id.unique'     => 'این کاربر قبلاً به عنوان کارشناس ثبت شده است.',
            'expert_code.unique' => 'این کد کارشناس قبلاً ثبت شده است.',
            'national_id.size'   => 'کد ملی باید ۱۰ رقم باشد.',
            'national_id.unique' => 'این کد ملی قبلاً ثبت شده است.',
            'mobile.size'        => 'شماره همراه باید ۱۱ رقم باشد.',
        ]);

        DB::transaction(function () use ($validated, $expert) {
            if ((string) $validated['user_id'] !== (string) $expert->user_id) {
                $expertRole = DB::table('behin_roles')->where('name', 'کارشناس')->first();
                if ($expertRole) {
                    User::query()->where('id', $validated['user_id'])->update([
                        'role_id' => $expertRole->id,
                    ]);
                }
            }

            $expert->update([
                'user_id'         => $validated['user_id'],
                'expert_code'     => $validated['expert_code'],
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'national_id'     => $validated['national_id'],
                'mobile'          => $validated['mobile'],
                'phone'           => $validated['phone'] ?? null,
                'province'        => $validated['province'],
                'city'            => $validated['city'],
                'address'         => $validated['address'],
                'is_certificated' => (bool) ($validated['is_certificated'] ?? false),
            ]);
        });

        return redirect()
            ->route('expert-catalog.index')
            ->with('success', 'اطلاعات کارشناس با موفقیت ویرایش شد.');
    }

    public function destroy(Expert $expert): RedirectResponse
    {
        $expert->delete();

        return redirect()
            ->route('expert-catalog.index')
            ->with('success', 'پروفایل کارشناس با موفقیت حذف شد.');
    }

    public function lastRecord(): JsonResponse
    {
        $expert = Expert::query()->latest()->first();

        return response()->json($expert);
    }
}
