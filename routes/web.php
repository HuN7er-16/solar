<?php

use App\Http\Controllers\InstallerRegistrationController;
use App\Models\User;
use BaleBot\Controllers\BotController;
use Behin\SimpleWorkflow\Controllers\Core\CaseController;
use Behin\SimpleWorkflow\Controllers\Core\PushNotifications;
use Behin\SimpleWorkflow\Controllers\Core\VariableController;
use Behin\SimpleWorkflow\Jobs\SendPushNotification;
use BehinInit\App\Http\Middleware\Access;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmeRegistrationController;
use SolarPlantRequests\Models\SolarPlantRequest;

Route::get('installer/apply', [InstallerRegistrationController::class, 'create'])->name('installers.apply');
Route::post('installer/apply', [InstallerRegistrationController::class, 'store'])->name('installers.store');

// API: شهرهای یک استان بر اساس نام استان
Route::get('api/cities', function (\Illuminate\Http\Request $request) {
    $provinceName = $request->query('province', '');

    // نرمال‌سازی: تبدیل ي و ك عربی به ی و ک فارسی و حذف فاصله‌ها برای مقایسه
    $normalize = fn(string $s): string => str_replace(
        ['ي', 'ك', 'ة', 'ؤ', 'إ', 'أ', ' ', "\u{200c}"],
        ['ی', 'ک', 'ه', 'و', 'ا', 'ا', '', ''],
        trim($s)
    );

    $needle = $normalize($provinceName);
    $cities = json_decode(file_get_contents(resource_path('data/cities.json')), true);
    $filtered = array_values(array_filter($cities, fn($c) => $normalize($c['provinceName']) === $needle));
    usort($filtered, fn($a, $b) => strcmp($a['cityName'], $b['cityName']));
    return response()->json($filtered);
})->name('api.cities');

Route::get('', function(){
    if(Auth::check()){
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
})->name('home');

require __DIR__.'/auth.php';
require __DIR__.'/services.php';

// داشبورد متقاضی: کاربران عادی (متقاضی) به این صفحه هدایت می‌شوند
Route::get('applicant/dashboard', function () {
    return view('admin.applicant-dashboard');
})->middleware(['web', 'auth'])->name('applicant.dashboard');

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', Access::class])->group(function(){
    Route::get('', function(){
        $user = Auth::user();

        // اگر کاربر هیچ‌یک از نقش‌های سیستمی را نداشت، متقاضی محسوب می‌شود
        $isStaff = SolarPlantRequest::userHasRole($user, 'leader')
            || SolarPlantRequest::userHasRole($user, 'contractor')
            || SolarPlantRequest::userHasRole($user, 'inspector')
            || SolarPlantRequest::userHasRole($user, 'expert');

        if (!$isStaff) {
            return redirect()->route('applicant.dashboard');
        }

        return view('admin.dashboard');
    })->name('dashboard');
});

Route::get('/pusher/beams-auth', function (Request $request) {
    $beamsClient = new PushNotifications([
        'instanceId' => config('broadcasting.pusher.instanceId'),
        'secretKey' => config('broadcasting.pusher.secretKey')
    ]);
    $userId = auth()->user()->id;
    $beamsToken = $beamsClient->generateToken('user-mobile-'.$userId);
    // $user = User::find($userId);
    return response()->json($beamsToken);
})->middleware('auth');

Route::get('send-notification', function () {
    SendPushNotification::dispatch(Auth::user()->id, 'test', 'test', route('admin.dashboard'));
    return 'تا دقایقی دیگر باید نوتیفیکیشن دریافت کنید';
})->name('send-notification');

Route::get('queue-work', function () {
    $limit = 5; // تعداد jobهای پردازش شده در هر درخواست
    $jobs = DB::table('jobs')->orderBy('id')->limit($limit)->get();

    foreach ($jobs as $job) {
        try {
            // دیکد کردن محتوای job
            $payload = json_decode($job->payload, true);
            $command = unserialize($payload['data']['command']);

            // اجرای job
            $command->handle();

            // حذف job پس از اجرا
            DB::table('jobs')->where('id', $job->id)->delete();

            // return 'Job processed: ' . $job->id;
        } catch (Exception $e) {
            // در صورت خطا، job را به جدول failed_jobs منتقل کنید
            DB::table('failed_jobs')->insert([
                'connection' => $job->connection ?? 'database',
                'queue' => $job->queue,
                'payload' => $job->payload,
                'exception' => (string) $e,
                'failed_at' => now()
            ]);

            DB::table('jobs')->where('id', $job->id)->delete();

            return 'Job failed: ' . $e->getMessage();
        }
    }
});

Route::get('build-app', function(){
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('migrate');
    return redirect()->back();
});

Route::get('migrate', function(){
    Artisan::call('migrate');
    return redirect()->back();
});

Route::get('migrate-catalogs', function(){
    $paths = [
        // base_path('packages/panel-catalog/src/Database/Migrations'),
        // base_path('packages/inverter-catalog/src/Database/Migrations'),
        // base_path('packages/battery-catalog/src/Database/Migrations'),
        // base_path('packages/contractor-catalog/src/Database/Migrations'),
        // base_path('packages/solar-plant-equipment/src/Database/Migrations'),
        // base_path('packages/inspector-catalog/src/Database/Migrations'),
        // base_path('packages/project-inspection/src/Database/Migrations'),
        // base_path('packages/expert-catalog/src/Database/Migrations'),
        // base_path('packages/request-expert-review/src/Database/Migrations'),
        base_path('packages/expert-initial-visit/src/Database/Migrations'),
    ];

    $logs = [];
    foreach ($paths as $path) {
        try {
            Artisan::call('migrate', ['--path' => str_replace(base_path().'/', '', $path), '--force' => true]);
            $logs[] = "✅ " . basename(dirname($path, 2)) . ":\n" . trim(Artisan::output());
        } catch (\Exception $e) {
            $logs[] = "❌ " . basename(dirname($path, 2)) . ": " . $e->getMessage();
        }
    }

    return response('<pre style="padding:20px; font-family:monospace; background:#f8f9fa;">' . implode("\n\n", $logs) . '</pre>');
});





Route::middleware(['web', 'auth'])->get('admin/seed-mock-data', function () {
    $startTime = microtime(true);
    $log = [];

    try {
        // 🔒 فقط برای role admin/بازرس (1 و 13)
        $uid = (int) auth()->id();
        $userRole = (int) (auth()->user()->role_id ?? 0);
        if (!in_array($userRole, [1, 13], true)) {
            abort(403, 'دسترسی مجاز نمی‌باشد.');
        }

        DB::beginTransaction();

        $rand24 = rand(2, 4);

        // ───────────────────────────────────────────────────────────
        // 1. USERS — بازرس (role=13) + متقاضی حقیقی + متقاضی حقوقی + پیمانکار (role=5)
        // ───────────────────────────────────────────────────────────
        $usersSeeded = 0;
        $userSamples = [
            ['نام' => 'محمدرضا گلدار',     'role' => 13, 'email' => '09121000001', 'number' => 'INS-001'],
            ['نام' => 'سارا احمدی',         'role' => 13, 'email' => '09121000002', 'number' => 'INS-002'],
            ['نام' => 'علی احمدزاده',      'role' => 5,  'email' => '09122000001', 'number' => 'CON-001'],
            ['نام' => 'مهندس رضایی',       'role' => 5,  'email' => '09122000002', 'number' => 'CON-002'],
            ['نام' => 'رضا محمدی',         'role' => 3,  'email' => '09123000001', 'number' => 'APP-001'],
            ['نام' => 'شرکت نیکو انرژی',   'role' => 3,  'email' => '09123000002', 'number' => 'APP-002'],
        ];
        $userIds = [];
        $inspectorUserIds = [];
        $contractorUserIds = [];
        $applicantUserIds = [];

        foreach (array_slice($userSamples, 0, rand(4, 6)) as $u) {
            $name = $u['نام'];
            $email = $u['email']; // موبایل در فیلد email ذخیره می‌شود
            try {
                $user = \App\Models\User::query()->firstOrCreate(
                    [
                        'name' => $name,
                        'number' => $u['number'],
                        'role_id' => $u['role'],
                        'email' => $email,
                        'password' => bcrypt('123456789'),
                        'email_verified_at' => now(),
                    ]
                );
                if ($user->wasRecentlyCreated) {
                    $usersSeeded++;
                }
                $userIds[$name] = $user->id;
                if ($u['role'] === 13) { $inspectorUserIds[] = $user->id; }
                if ($u['role'] === 5)  { $contractorUserIds[] = $user->id; }
                if ($u['role'] === 3)  { $applicantUserIds[] = $user->id; }
            } catch (\Throwable $e) {
                $log[] = '⚠️ کاربر ' . $name . ': ' . $e->getMessage();
            }
        }
        $log[] = '✅ Users: ایجاد ' . $usersSeeded . ' کاربر جدید ' . '(' . count($userIds) . ' کاربر موجود)';

        // ───────────────────────────────────────────────────────────
        // 2. CONTRACTORS کاتالوگ پیمانکاران
        // ───────────────────────────────────────────────────────────
        $provinces = ['تهران', 'اصفهان', 'فارس', 'خراسان رضوی', 'گیلان', 'آذربایجان شرقی'];
        $cities    = ['تهران', 'اصفهان', 'شیراز', 'مشهد', 'رشت', 'تبریز'];
        $contractorSamples = [
            ['company_name' => 'پیکاسو انرژی آریا',   'ceo_name' => 'علی احمدزاده',   'national_id' => '1400123456'],
            ['company_name' => 'آفتاب پاک سپهر',     'ceo_name' => 'مهندس رضایی',    'national_id' => '1400654321'],
            ['company_name' => 'نیروگاه خورشیدی آسمان', 'ceo_name' => 'حسین کریمی',   'national_id' => '1400789012'],
        ];
        $contractorIds = [];
        $ctr = 0;
        foreach (array_slice($contractorSamples, 0, $rand24) as $i => $c) {
            $prov = $provinces[array_rand($provinces)];
            $city = $cities[array_rand($cities)];
            try {
                $contractor = \ContractorCatalog\Models\Contractor::query()->firstOrCreate(
                    ['national_id' => $c['national_id']],
                    [
                        'company_name' => $c['company_name'],
                        'ceo_name' => $c['ceo_name'],
                        'ceo_national_code' => (string) rand(1000000000, 9999999999),
                        'ceo_mobile' => '0912' . str_pad((string) rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                        'contact_person_name' => $c['ceo_name'],
                        'contact_person_mobile' => '0912' . str_pad((string) rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                        'company_phone' => '021' . str_pad((string) rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                        'province' => $prov,
                        'city' => $city,
                        'address' => "$prov - $city - خیابان نمونه، پلاک " . rand(1, 200),
                        'license_number' => 'LP-' . strtoupper(bin2hex(random_bytes(3))),
                        'license_issue_date' => now()->subYears(rand(1, 6)),
                        'license_expiry_date' => now()->addYears(rand(1, 4)),
                        'registered_projects_count' => rand(10, 200),
                    ]
                );
                if ($contractor->wasRecentlyCreated) { $ctr++; }
                $contractorIds[] = $contractor->id;
            } catch (\Throwable $e) {
                $log[] = '⚠️ پیمانکار ' . ($i + 1) . ': ' . $e->getMessage();
            }
        }
        $log[] = '✅ Contractors: ایجاد ' . $ctr . ' پیمانکار جدید (' . count($contractorIds) . ' پیمانکار موجود)';

        // ───────────────────────────────────────────────────────────
        // 3. INSPECTORS کاتالوگ بازرسها
        // ───────────────────────────────────────────────────────────
        $inspectorCats = [
            ['inspector_code' => 'INS-CODE-1001', 'first_name' => 'محمدرضا', 'last_name' => 'گلدار',  'national_id' => '0011223344'],
            ['inspector_code' => 'INS-CODE-1002', 'first_name' => 'سارا',     'last_name' => 'احمدی',   'national_id' => '0055667788'],
            ['inspector_code' => 'INS-CODE-1003', 'first_name' => 'محمد',    'last_name' => 'طاهری',   'national_id' => '0099887766'],
        ];
        $inspectorCatalogIds = [];
        $ins = 0;
        foreach (array_slice($inspectorCats, 0, $rand24) as $i => $ic) {
            $prov = $provinces[array_rand($provinces)];
            $city = $cities[array_rand($cities)];
            $linkedUserId = $inspectorUserIds[$i] ?? ($inspectorUserIds[0] ?? $uid);
            try {
                $record = \InspectorCatalog\Models\Inspector::query()->firstOrCreate(
                    ['inspector_code' => $ic['inspector_code']],
                    [
                        'user_id' => $linkedUserId,
                        'first_name' => $ic['first_name'],
                        'last_name' => $ic['last_name'],
                        'national_id' => $ic['national_id'],
                        'mobile' => '0912' . str_pad((string) rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                        'phone'  => '021' . str_pad((string) rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                        'province' => $prov,
                        'city' => $city,
                        'address' => "$prov - $city - بلوار آزادی، کوچه نمونه",
                        'is_certificated' => true,
                    ]
                );
                if ($record->wasRecentlyCreated) { $ins++; }
                $inspectorCatalogIds[] = $record->id;
            } catch (\Throwable $e) {
                $log[] = '⚠️ بازرس ' . ($i + 1) . ': ' . $e->getMessage();
            }
        }
        $log[] = '✅ Inspector Catalog: ایجاد ' . $ins . ' بازرس جدید';

        // ───────────────────────────────────────────────────────────
        // 4. PANEL CATALOG کاتالوگ پنل خورشیدی
        // ───────────────────────────────────────────────────────────
        $panelSamples = [
            ['brand' => 'Jinko Solar',   'model' => 'JKM550M-72HL4-V', 'model_code' => 'JK550HL4', 'technology' => 'N-Type TOPCon', 'panel_type' => 'Monocrystalline', 'rated_power_wp' => 550, 'module_efficiency' => 21.78],
            ['brand' => 'Trina Solar',   'model' => 'TSM-DE19R.08',    'model_code' => 'TR580',    'technology' => 'N-Type',       'panel_type' => 'Monocrystalline', 'rated_power_wp' => 580, 'module_efficiency' => 22.10],
            ['brand' => 'JA Solar',      'model' => 'DeepBlue 4.0',    'model_code' => 'JA610',    'technology' => 'PERC',         'panel_type' => 'Monocrystalline', 'rated_power_wp' => 610, 'module_efficiency' => 22.30],
        ];
        $panelCatIds = [];
        $pn = 0;
        foreach (array_slice($panelSamples, 0, $rand24) as $p) {
            try {
                $rec = \PanelCatalog\Models\PanelCatalog::query()->firstOrCreate(
                    ['model_code' => $p['model_code']],
                    array_merge($p, [
                        'manufacture' => $p['brand'],
                        'country_of_manufacture' => ['چین', 'کره جنوبی', 'آمریکا', 'تایوان'][rand(0, 3)],
                        'number_of_cells' => 144,
                        'cell_type' => 'M10',
                        'voc' => round(49 + lcg_value(), 2),
                        'isc' => round(13 + lcg_value(), 2),
                        'vmp' => round(42 + lcg_value(), 2),
                        'imp' => round(12 + lcg_value(), 2),
                        'max_system_voltage' => 1500,
                        'temperature_coefficient' => -0.34,
                        'power_tolerance' => '+5W',
                        'product_warranty' => 15,
                        'performance_warranty' => 30,
                        'iec_61215' => true,
                        'iec_61730' => true,
                        'connector_type' => 'MC4-EVO2',
                        'dimensions' => '2278×1134×30mm',
                        'weight' => 27.5,
                        'union_approval_status' => 'approved',
                        'lab_certified' => true,
                        'lab_name' => 'آزمایشگاه انرژی تجدیدپذیر',
                    ])
                );
                if ($rec->wasRecentlyCreated) { $pn++; }
                $panelCatIds[] = $rec->id;
            } catch (\Throwable $e) {
                $log[] = '⚠️ پنل ' . ($pn + 1) . ': ' . $e->getMessage();
            }
        }
        $log[] = '✅ Panel Catalog: ایجاد ' . $pn . ' پنل جدید (' . count($panelCatIds) . ' پنل)';

        // ───────────────────────────────────────────────────────────
        // 5. INVERTER CATALOG کاتالوگ اینورتر
        // ───────────────────────────────────────────────────────────
        $inverterSamples = [
            ['brand' => 'Huawei',   'model_name' => 'SUN2000-100KTL-M1', 'model_code' => 'HW100KTL', 'inverter_type' => 'Three Phase On-Grid', 'rated_power_kw' => 100],
            ['brand' => 'Sungrow',  'model_name' => 'SG110CX-P2',        'model_code' => 'SG110CX',   'inverter_type' => 'Hybrid',             'rated_power_kw' => 110],
            ['brand' => 'Solis',    'model_name' => 'S6-GR1P(3-6)K',    'model_code' => 'SOL5K',     'inverter_type' => 'Single Phase',       'rated_power_kw' => 5],
        ];
        $inverterCatIds = [];
        $inv = 0;
        foreach (array_slice($inverterSamples, 0, $rand24) as $iv) {
            try {
                $rec = \InverterCatalog\Models\InverterCatalog::query()->firstOrCreate(
                    ['model_code' => $iv['model_code']],
                    array_merge($iv, [
                        'manufacture' => $iv['brand'],
                        'country_of_manufacture' => ['چین', 'آلمان', 'ایتالیا'][rand(0, 2)],
                        'mppt_count' => $iv['rated_power_kw'] < 15 ? 2 : 6,
                        'strings_per_mppt' => 2,
                        'max_dc_input_voltage' => 1100,
                        'max_input_current' => 30,
                        'max_output_current' => round($iv['rated_power_kw'] * 1.52, 1),
                        'output_voltage' => 400,
                        'output_frequency' => 50,
                        'max_efficiency' => round(98.2 + lcg_value() * 1.5, 2),
                        'protection_level' => 'IP66',
                        'cooling_type' => 'Natural/Intelligent Fan',
                        'dc_switch' => true,
                        'ac_switch' => true,
                        'reverse_polarity_protection' => true,
                        'display' => true,
                        'anti_islanding_protection' => true,
                        'leakage_current_protection' => true,
                        'spd_type' => true,
                        'thd' => round(lcg_value() * 3, 2),
                        'mpp_voltage_range' => '200–1000 V',
                        'communication_protocols' => ['Modbus RTU', 'RS485', 'WiFi', '4G'],
                        'max_pv_input_power' => $iv['rated_power_kw'] * 1.2,
                        'warranty_period' => '10 سال (قابل تمدید تا 20)',
                        'standards' => ['IEC 61683', 'EN 50549', 'VDE 0126'],
                        'lab_certified' => true,
                        'lab_name' => 'TÜV Rheinland',
                    ])
                );
                if ($rec->wasRecentlyCreated) { $inv++; }
                $inverterCatIds[] = $rec->id;
            } catch (\Throwable $e) {
                $log[] = '⚠️ اینورتر: ' . $e->getMessage();
            }
        }
        $log[] = '✅ Inverter Catalog: ایجاد ' . $inv . ' اینورتر جدید';

        // ───────────────────────────────────────────────────────────
        // 6. BATTERY CATALOG کاتالوگ باتری
        // ───────────────────────────────────────────────────────────
        $batterySamples = [
            ['brand' => 'BYD',          'model_name' => 'Battery-Box Premium HVS', 'model_code' => 'BYD-HVS',   'battery_type' => 'LiFePO4', 'energy_capacity_kwh' => 10.24],
            ['brand' => 'Tesla',        'model_name' => 'Powerwall 2',            'model_code' => 'PW2',         'battery_type' => 'Li-ion',   'energy_capacity_kwh' => 13.5],
            ['brand' => 'Huawei Luna',  'model_name' => 'LUNA2000-15-S0',         'model_code' => 'LUNA15',      'battery_type' => 'LiFePO4',   'energy_capacity_kwh' => 15],
        ];
        $batteryCatIds = [];
        $bt = 0;
        foreach (array_slice($batterySamples, 0, $rand24) as $b) {
            try {
                $rec = \BatteryCatalog\Models\BatteryCatalog::query()->firstOrCreate(
                    ['model_code' => $b['model_code']],
                    array_merge($b, [
                        'manufacture' => $b['brand'],
                        'country_of_manufacture' => ['چین', 'آمریکا', 'آلمان'][rand(0, 2)],
                        'capacity_ah' => 200,
                        'nominal_voltage' => $b['energy_capacity_kwh'] > 12 ? 400 : 51.2,
                        'max_charge_current' => 100,
                        'max_discharge_current' => 100,
                        'cycle_life' => 6000,
                        'depth_of_discharge' => 95,
                        'expandable' => true,
                        'max_parallel_units' => 8,
                        'ip_rating' => 'IP65',
                        'communication_protocols' => ['CAN', 'RS485'],
                        'dimensions' => '800×600×200mm',
                        'weight' => 160,
                        'warranty_years' => 10,
                        'standards' => ['IEC 62619', 'UL9540', 'UN38.3'],
                        'union_approved' => true,
                        'union_approval_date' => now()->subMonths(rand(3, 20)),
                        'lab_certified' => true,
                        'lab_name' => 'TÜV',
                    ])
                );
                if ($rec->wasRecentlyCreated) { $bt++; }
                $batteryCatIds[] = $rec->id;
            } catch (\Throwable $e) {
                $log[] = '⚠️ باتری: ' . $e->getMessage();
            }
        }
        $log[] = '✅ Battery Catalog: ایجاد ' . $bt . ' باتری جدید';

        // ───────────────────────────────────────────────────────────
        // 7. SOLAR PLANT REQUESTS درخواست‌های نیروگاه
        // ───────────────────────────────────────────────────────────
        $sampleApplicants = [
            [
                'type' => 'natural',
                'first_name' => 'محمد', 'last_name' => 'رضایی',
                'national_code' => '1112223334',
                'province' => 'تهران', 'city' => 'تهران',
                'capacity_kw' => 20, 'postal_code' => '1411711111',
                'address' => 'تهران - خیابان ولیعصر - کوچه دوم - پلاک 17',
            ],
            [
                'type' => 'legal',
                'company_name' => 'شرکت انرژی پایدار آریا', 'registration_number' => '5080123456',
                'ceo_national_id' => '2223334445',
                'province' => 'اصفهان', 'city' => 'اصفهان',
                'capacity_kw' => 100, 'postal_code' => '8141812345',
                'address' => 'اصفهان - محوطه صنعتی ۴ - بلوار صنعت',
            ],
            [
                'type' => 'natural',
                'first_name' => 'فاطمه', 'last_name' => 'سلیمانی',
                'national_code' => '3334445556',
                'province' => 'فارس', 'city' => 'شیراز',
                'capacity_kw' => 50, 'postal_code' => '7135867890',
                'address' => 'شیراز - معالی آباد - خیابان شهید باهنر',
            ],
        ];
        $requestIds = [];
        $reqCount = 0;
        $reqContractorIds = $contractorIds;
        $reqInspectorIds = $inspectorUserIds;

        foreach (array_slice($sampleApplicants, 0, $rand24) as $idx => $sa) {
            $applicantUserId = $applicantUserIds[0] ?? $uid;
            $pctrId = (!empty($reqContractorIds)) ? $reqContractorIds[array_rand($reqContractorIds)] : null;
            $inspectorId = (!empty($reqInspectorIds)) ? $reqInspectorIds[array_rand($reqInspectorIds)] : null;

            $payload = [
                'user_id' => $applicantUserId,
                'applicant_type' => $sa['type'] === 'natural' ? 'natural' : 'legal',
                'first_name' => $sa['first_name'] ?? null,
                'last_name'  => $sa['last_name'] ?? null,
                'company_name' => $sa['company_name'] ?? null,
                'registration_number' => $sa['registration_number'] ?? null,
                'ceo_national_id' => $sa['ceo_national_id'] ?? null,
                'national_code' => $sa['national_code'] ?? null,
                'mobile' => '0912' . str_pad((string) rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'landline' => '021' . str_pad((string) rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'province' => $sa['province'],
                'city' => $sa['city'],
                'postal_code' => $sa['postal_code'],
                'address' => $sa['address'],
                'bill_identifier' => (string) rand(1000000000, 9999999999),
                'area' => rand(50, 500),
                'usage_type' => ['residential', 'commercial', 'industrial'][array_rand(['residential', 'commercial', 'industrial'])],
                'is_shared_property' => (bool) rand(0, 1),
                'installation_area' => $sa['capacity_kw'] * 7,
                'surface_type' => ['flat_roof', 'ground_mount', 'carport'][rand(0, 2)],
                'purpose' => 'self_consumption',
                'capacity_kw' => $sa['capacity_kw'],
                'has_three_phase' => $sa['capacity_kw'] >= 10,
                'wants_loan' => (bool) rand(0, 1),
                'description' => 'درخواست نصب نیروگاه خورشیدی ' . $sa['capacity_kw'] . ' کیلوواتی در ' . $sa['city'],
                'contractor_id' => $pctrId,
                'inspector_user_id' => $inspectorId,
                'status' => 'equipment_installation',
            ];

            try {
                $unique = 'SPR' . time() . strtoupper(bin2hex(random_bytes(2)));
                $req = \SolarPlantRequests\Models\SolarPlantRequest::query()->firstOrCreate(
                    ['unique_code' => $unique],
                    $payload
                );
                if ($req->wasRecentlyCreated) { $reqCount++; }
                $requestIds[] = $req->id;
            } catch (\Throwable $e) {
                $log[] = '⚠️ درخواست: ' . $e->getMessage();
            }
        }
        $log[] = '✅ Solar Plant Requests: ایجاد ' . $reqCount . ' درخواست جدید';

        // ───────────────────────────────────────────────────────────
        // 8. SOLAR PROJECTS پروژه‌های نیروگاه
        // ───────────────────────────────────────────────────────────
        $projectIds = [];
        $pCount = 0;
        $sampleStatuses = [
            \SolarPlantEquipment\Models\SolarProject::STATUS_IN_PROGRESS,
            \SolarPlantEquipment\Models\SolarProject::STATUS_READY_FOR_INSPECTION,
            \SolarPlantEquipment\Models\SolarProject::STATUS_APPROVED,
        ];

        for ($i = 0; $i < $rand24; $i++) {
            $reqId = (empty($requestIds)) ? null : $requestIds[$i % count($requestIds)];
            $pctrId = (empty($contractorIds)) ? null : $contractorIds[$i % count($contractorIds)];
            $inspectorId = (empty($inspectorUserIds)) ? null : $inspectorUserIds[$i % count($inspectorUserIds)];
            $status = $sampleStatuses[$i % count($sampleStatuses)];

            $capacity = [5, 10, 20, 50, 100][array_rand([5, 10, 20, 50, 100])];
            $hcNo = null;
            $hcIssue = null;
            $hcExpiry = null;
            if ($status === \SolarPlantEquipment\Models\SolarProject::STATUS_APPROVED) {
                $hcNo = 'HC-' . strtoupper(bin2hex(random_bytes(4)));
                $hcIssue = now()->subDays(rand(1, 60));
                $hcExpiry = $hcIssue->copy()->addYears(2);
            }

            try {
                $rec = \SolarPlantEquipment\Models\SolarProject::query()->create([
                    'request_id' => $reqId,
                    'contractor_id' => $pctrId,
                    'inspector_id' => $inspectorId,
                    'installation_start_date' => now()->subDays(rand(20, 150)),
                    'installation_end_date' => ($status !== \SolarPlantEquipment\Models\SolarProject::STATUS_IN_PROGRESS) ? now()->subDays(rand(1, 20)) : null,
                    'commissioning_date' => ($status === \SolarPlantEquipment\Models\SolarProject::STATUS_APPROVED) ? now()->subDays(rand(1, 10)) : null,
                    'latitude' => (float) number_format(25 + rand(0, 15) + lcg_value(), 6),
                    'longitude' => (float) number_format(44 + rand(0, 15) + lcg_value(), 6),
                    'satba_contract_number' => (rand(0, 1)) ? 'SATBA-' . rand(10000, 99999) . '/' . (1400 + rand(0, 5)) : null,
                    'status' => $status,
                    'health_card_no' => $hcNo,
                    'health_card_issue_date' => $hcIssue,
                    'health_card_expiry_date' => $hcExpiry,
                    'description' => 'پروژه خورشیدی نمونه با ظرفیت ' . $capacity . ' کیلووات — ایجاد شده از طریق Seed Mock Data',
                ]);
                $projectIds[] = $rec->id;
                $pCount++;
            } catch (\Throwable $e) {
                $log[] = '⚠️ پروژه: ' . $e->getMessage();
            }
        }
        $log[] = '✅ Solar Projects: ایجاد ' . $pCount . ' پروژه جدید';

        // ───────────────────────────────────────────────────────────
        // 9. INSTALLED PANELS / INVERTERS / BATTERIES — تجهیز نصب‌شده
        // ───────────────────────────────────────────────────────────
        $installedCounts = [0, 0, 0];
        foreach ($projectIds as $pjIdx => $pid) {
            $panelCat = $panelCatIds[$pjIdx % (max(1, count($panelCatIds)))];
            $panelQty = rand(4, 12);
            for ($k = 0; $k < $panelQty; $k++) {
                try {
                    $ip = \SolarPlantEquipment\Models\InstalledPanel::query()->create([
                        'project_id' => $pid,
                        'panel_model_id' => $panelCat,
                        'serial_number' => 'PNL-' . date('y') . '-' . strtoupper(bin2hex(random_bytes(3))),
                        'section_number' => 1,
                        'string_number' => (int) ceil(($k + 1) / 6),
                        'panel_number' => $k + 1,
                        'status' => 'active',
                    ]);
                    $installedCounts[0]++;
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            if (!empty($inverterCatIds)) {
                $invCat = $inverterCatIds[$pjIdx % count($inverterCatIds)];
                $invQty = rand(1, 2);
                for ($k = 0; $k < $invQty; $k++) {
                    try {
                        \SolarPlantEquipment\Models\InstalledInverter::query()->create([
                            'project_id' => $pid,
                            'inverter_model_id' => $invCat,
                            'serial_number' => 'INV-' . date('y') . '-' . strtoupper(bin2hex(random_bytes(3))),
                            'equipment_tag' => 'INV-' . ($k + 1),
                            'installation_location' => ['electrical_room', 'control_room', 'outdoor'][rand(0, 2)],
                            'status' => 'active',
                        ]);
                        $installedCounts[1]++;
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }

            if (!empty($batteryCatIds) && rand(0, 1)) {
                $batCat = $batteryCatIds[$pjIdx % count($batteryCatIds)];
                $batQty = rand(1, 3);
                for ($k = 0; $k < $batQty; $k++) {
                    try {
                        \SolarPlantEquipment\Models\InstalledBattery::query()->create([
                            'project_id' => $pid,
                            'battery_model_id' => $batCat,
                            'serial_number' => 'BAT-' . date('y') . '-' . strtoupper(bin2hex(random_bytes(3))),
                            'equipment_tag' => 'BAT-' . ($k + 1),
                            'installation_location' => ['battery_rack', 'battery_cabinet', 'battery_room'][rand(0, 2)],
                            'status' => 'active',
                        ]);
                        $installedCounts[2]++;
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }
        }
        $log[] = '✅ تجهیز نصب‌شده: ' . $installedCounts[0] . ' پنل / ' . $installedCounts[1] . ' اینورتر / ' . $installedCounts[2] . ' باتری';

        // ───────────────────────────────────────────────────────────
        // 10. PROJECT INSPECTIONS — ثبت رکورد بازرسی برای پروژه درحال بازرسی
        // ───────────────────────────────────────────────────────────
        $inspCount = 0;
        if (class_exists(\ProjectInspection\Models\ProjectInspection::class)) {
            foreach ($projectIds as $pid) {
                if (empty($inspectorUserIds)) { break; }
                $proj = \SolarPlantEquipment\Models\SolarProject::query()->find($pid);
                if (!$proj || !$proj->inspector_id) { continue; }

                $result = (rand(0, 100) > 30) ? 'approved' : 'rejected';
                $vdt = now()->subDays(rand(1, 30));

                $allBooleans = [];
                foreach (range(1, 60) as $ix) {
                    $allBooleans[] = true;
                }

                try {
                    $allFields = [
                        'inspector_id' => $proj->inspector_id,
                        'project_id' => $pid,
                        'visit_date' => $vdt,
                        'result' => $result,
                        'rejection_reason' => ($result === 'rejected') ? 'برخی از بخش‌ها نیازمند اصلاح و بازنگردانی هستند.' : null,
                        'project_info_matches_system' => true,
                        'plant_capacity_correct' => true,
                        'installation_location_correct' => true,
                        'project_info_notes' => 'تطابق کامل اطلاعات پروژه با سیستم',

                        'panel_brand_union_approved' => true,
                        'panel_brand_matches_project' => true,
                        'panel_model_approved' => true,
                        'panel_serial_correct' => true,
                        'panel_quantity_correct' => true,
                        'panel_intact' => true,
                        'panel_orientation_correct' => true,
                        'panel_angle_correct' => true,
                        'panel_notes' => 'پنل‌ها به درستی نصب و سر رشته شده‌اند.',

                        'structure_standard' => true,
                        'bolts_tightened' => true,
                        'no_corrosion' => true,
                        'proper_ground_clearance' => true,
                        'structure_notes' => 'ساختار گالوانیزه، استاندارد و بدون خوردگی.',

                        'cable_standard' => true,
                        'proper_cross_section' => true,
                        'proper_cabling' => true,
                        'cable_labeled' => true,
                        'dc_cabling_notes' => 'کابل‌کشی DC استاندارد و برچسب‌گذاری شده است.',

                        'inverter_brand_union_approved' => true,
                        'inverter_model_matches_project' => true,
                        'inverter_serial_correct' => true,
                        'inverter_installed_correctly' => true,
                        'ac_output_correct' => true,
                        'inverter_display_ok' => true,
                        'communication_ok' => true,
                        'inverter_notes' => 'تنظیمات اینورتر انجام شده و ارتباط برقرار است.',

                        'battery_brand_approved' => true,
                        'battery_model_matches_project' => true,
                        'battery_serial_correct' => true,
                        'battery_cabling_ok' => true,
                        'battery_voltage_ok' => true,
                        'battery_bms_ok' => true,
                        'battery_ventilation_ok' => true,
                        'battery_notes' => 'باتری‌ها نصب و تست شده‌اند.',

                        'grounding_electrode_present' => true,
                        'grounding_cable_size_ok' => true,
                        'spd_dc_present' => true,
                        'spd_ac_present' => true,
                        'proper_connections' => true,
                        'grounding_notes' => 'سیستم ارت و SPD نصب و کنترل شده است.',

                        'panelboard_standard' => true,
                        'labeled_circuits' => true,
                        'protections_coordinated' => true,
                        'electrical_panel_notes' => 'تابلو برق استاندارد و مدارها برچسب‌دار هستند.',

                        'generation_within_expected' => true,
                        'performance_ratio_acceptable' => true,
                        'monitoring_active' => true,
                        'alarms_verified' => true,
                        'performance_notes' => 'خروجی نیروگاه در محدوده مورد انتظار است.',

                        'safety_signs_present' => true,
                        'fire_extinguisher_available' => true,
                        'emergency_shutdown_accessible' => true,
                        'barriers_installed' => true,
                        'safety_notes' => 'اقدامات ایمنی تجهیزات انجام شده است.',
                    ];

                    \ProjectInspection\Models\ProjectInspection::query()->create($allFields);
                    $inspCount++;

                    // Update project inspection_ids
                    if ($proj) {
                        $existingIds = is_array($proj->inspection_ids) ? $proj->inspection_ids : [];
                        $lastInsp = \ProjectInspection\Models\ProjectInspection::query()->latest()->first();
                        if ($lastInsp && !in_array($lastInsp->id, $existingIds, true)) {
                            $existingIds[] = $lastInsp->id;
                            $proj->inspection_ids = $existingIds;
                            $proj->save();
                        }
                        // اگر پروژه approved بود، وضعیت را هم آپدیت کن
                        if ($result === 'approved' && $proj->status !== \SolarPlantEquipment\Models\SolarProject::STATUS_APPROVED) {
                            $proj->status = \SolarPlantEquipment\Models\SolarProject::STATUS_READY_FOR_INSPECTION;
                            $proj->save();
                        }
                    }
                } catch (\Throwable $e) {
                    $log[] = '⚠️ بازرسی پروژه ' . $pid . ': ' . $e->getMessage();
                }
            }
        }
        $log[] = '✅ Project Inspections: ایجاد ' . $inspCount . ' رکورد بازرسی جدید';

        DB::commit();

        $endTime = microtime(true);
        $total = round($endTime - $startTime, 2);
        $ok  = '<div style="color:#155724; background:#d4edda; border:1px solid #c3e6cb; border-radius:6px; padding:14px 18px; font-weight:bold; margin-bottom:14px;">
                    🎉 تولید داده‌های Mock با موفقیت انجام شد (زمان اجرا: ' . $total . ' ثانیه)
                </div>';
        $body = '<ul dir="rtl" style="line-height:2; padding: 0 22px;">';
        foreach ($log as $line) {
            $body .= '<li>' . $line . '</li>';
        }
        $body .= '</ul>';
        $actions = '<div style="margin-top:18px; display:flex; gap:10px; justify-content:center;">
            <a href="' . route('admin.dashboard') . '" style="padding:8px 18px; background:#007bff; color:#fff; text-decoration:none; border-radius:6px;">⬅️ بازگشت به داشبورد</a>
            <a href="' . route('solar-plant-equipment.projects.index') . '" style="padding:8px 18px; background:#28a745; color:#fff; text-decoration:none; border-radius:6px;">📋 مشاهده لیست پروژه‌ها</a>
            <a href="' . route('project-inspection.inspections.index') . '" style="padding:8px 18px; background:#c82333; color:#fff; text-decoration:none; border-radius:6px;">🔍 مشاهده بازرسی‌ها</a>
        </div>';

        return response('
            <!doctype html><html dir="rtl" lang="fa"><head><meta charset="utf-8">
            <title>نتیجه تولید داده Mock</title>
            <body style="font-family:Tahoma; max-width:850px; margin: 30px auto; padding: 20px; background:#fff; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08);">
            ' . $ok . $body . $actions . '
            </body></html>
        ');
    } catch (\Throwable $e) {
        DB::rollBack();
        return response('
            <!doctype html><html dir="rtl" lang="fa"><head><meta charset="utf-8"><title>خطا در تولید داده Mock</title>
            <body style="font-family:Tahoma; max-width:850px; margin: 30px auto; padding: 20px; background:#f8d7da; border-radius:10px; color:#721c24;">
            <h2>❌ خطا در تولید داده Mock</h2>
            <p><strong>پیام خطا:</strong> ' . nl2br(e($e->getMessage())) . '</p>
            <p><strong>فایل:</strong> ' . e($e->getFile()) . ' <strong>خط:</strong> ' . $e->getLine() . '</p>
            <details><summary>Stack Trace</summary><pre style="direction:ltr; text-align:left; background:#fff; padding:10px; overflow:auto; max-height:300px;">' . e($e->getTraceAsString()) . '</pre></details>
            <p><a href="' . route('admin.dashboard') . '" style="color:#007bff;">⬅️ بازگشت به داشبورد</a></p>
            </body></html>
        ', 500);
    }
})->name('admin.seed-mock-data');


Route::get('setup-packages', function(){
    try {
        $startTime = microtime(true);
        $logs = [];
        
        // 1. Dump Autoload (شبیه‌سازی composer dump-autoload)
        $logs[] = "🔄 در حال بارگذاری مجدد Autoload...";
        Artisan::call('clear-compiled');
        Artisan::call('optimize:clear');
        
        // 2. Clear all caches
        $logs[] = "🧹 پاک‌سازی Cache...";
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('event:clear');
        
        // 3. Run migrations
        $logs[] = "📊 اجرای Migrations...";
        Artisan::call('migrate', ['--force' => true]);
        $migrationOutput = Artisan::output();
        
        // 4. Create storage link if not exists
        if (!file_exists(public_path('storage'))) {
            $logs[] = "🔗 ایجاد Storage Link...";
            Artisan::call('storage:link');
        } else {
            $logs[] = "✓ Storage Link از قبل موجود است";
        }
        
        // 5. Optimize for production
        $logs[] = "⚡ بهینه‌سازی برای Production...";
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        
        // 6. List all registered routes
        $logs[] = "\n📋 لیست Route های ثبت شده:";
        $routes = collect(Route::getRoutes())->filter(function($route){
            return str_contains($route->getName() ?? '', 'catalog') || 
                   str_contains($route->uri(), 'catalog');
        })->map(function($route){
            return "  - " . ($route->getName() ?? $route->uri()) . " → " . url($route->uri());
        })->values()->toArray();
        
        $logs = array_merge($logs, $routes);
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        
        // Format output
        $output = "✅ راه‌اندازی پکیج‌ها با موفقیت انجام شد!\n";
        $output .= "⏱️  زمان اجرا: {$executionTime} ثانیه\n";
        $output .= str_repeat("─", 60) . "\n\n";
        $output .= implode("\n", $logs);
        $output .= "\n\n" . str_repeat("─", 60) . "\n";
        $output .= "📝 خروجی Migration:\n" . trim($migrationOutput);
        $output .= "\n\n" . str_repeat("─", 60) . "\n";
        $output .= "✨ تمام پکیج‌ها آماده استفاده هستند!\n\n";
        $output .= "⚠️  توجه: اگر کلاس‌ها شناخته نشدند، از terminal سرور اجرا کنید:\n";
        $output .= "   composer dump-autoload";
        
        return response('<pre style="direction:rtl; text-align:right; padding:20px; background:#f8f9fa; border:1px solid #ddd; border-radius:5px; font-family:Tahoma; white-space:pre-wrap;">' . $output . '</pre>');
        
    } catch (\Exception $e) {
        $error = "❌ خطا در راه‌اندازی:\n\n";
        $error .= "پیام خطا: " . $e->getMessage() . "\n";
        $error .= "فایل: " . $e->getFile() . "\n";
        $error .= "خط: " . $e->getLine() . "\n\n";
        $error .= "Stack Trace:\n" . $e->getTraceAsString();
        
        return response('<pre style="direction:rtl; text-align:right; padding:20px; background:#fff3cd; border:1px solid #ffc107; border-radius:5px; font-family:Tahoma; white-space:pre-wrap;">' . $error . '</pre>');
    }
})->name('setup-packages');
