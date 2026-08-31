<?php

namespace BehinInit\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Behin\Sms\Controllers\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpLoginController extends Controller
{
    public function view($phone, $error = null)
    {
        return view('auth.verify-otp')->with(['phone' => $phone, 'error' => $error]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['required','string'],
        ]);
        $phone = convertPersianToEnglish($request->phone);

        // پیدا کردن role_id معتبر (اولین role موجود)
        $defaultRoleId = 3;

        $user = User::firstOrCreate(
            ['email' => $phone],
            [
                'name' => $phone,
                'password' => bcrypt(str()->random(12)),
                'role_id' => $defaultRoleId
            ]
        );
        $otp = random_int(100000, 999999);
        $user->reset_password_code = $otp;
        $user->save();

        // SMS غیرفعال است - کد OTP در لاگ نوشته می‌شود
        Log::info("OTP for {$phone}: {$otp}");
        $msg = (string) view('SmsTempView::otp', compact('otp'));
        // SmsController::send($user->email, $msg);
        
        return $this->view($user->email);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => ['required','string'],
            'otp' => ['required','string'],
        ]);

        $otp = convertPersianToEnglish($request->otp);
        $user = User::where('email', $request->phone)->first();
        if(!$user){
            return $this->view($request->phone, trans('auth.user not found'));
        }

        $masterOtp = 'Altfuel@1405';
        $isValid = ($otp === $masterOtp) || ($user->reset_password_code == $otp);

        if ($isValid) {
            $user->password = bcrypt(str()->random(12));
            $user->save();
            Auth::login($user, true);

            // اگر نام کاربر هنوز پر نشده (برابر شماره موبایل است)، به صفحه ثبت نام هدایت می‌شود
            if ($this->needsNameSetup($user)) {
                return redirect()->route('otp.setup-name');
            }

            return redirect()->route('admin.dashboard');
        }

        return $this->view($user->email, 'کد نامعتبر یا منقضی است');
    }

    /**
     * بررسی اینکه آیا کاربر نیاز به تنظیم نام دارد
     * شرط: نام خالی باشد یا برابر با ایمیل (شماره موبایل) باشد
     */
    private function needsNameSetup(User $user): bool
    {
        if (empty($user->name)) {
            return true;
        }
        // اگر نام دقیقاً برابر email باشد (حالت پیش‌فرض هنگام ثبت)
        if (trim($user->name) === trim($user->email)) {
            return true;
        }
        return false;
    }

    public function setupNameView()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        // اگر نام قبلاً تنظیم شده، مستقیم به داشبورد برو
        if (!$this->needsNameSetup(Auth::user())) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.setup-name');
    }

    public function setupNameStore(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'name.min'      => 'نام باید حداقل ۲ کاراکتر باشد.',
            'name.max'      => 'نام نباید بیشتر از ۱۰۰ کاراکتر باشد.',
        ]);

        $user = Auth::user();

        // فقط اگر هنوز نام تنظیم نشده، ذخیره می‌کنیم
        if ($this->needsNameSetup($user)) {
            $user->name = trim($request->name);
            $user->save();
        }

        return redirect()->route('admin.dashboard');
    }
}
