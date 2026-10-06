<?php

namespace SolarPlantEquipment\Http\Controllers;

use App\Models\User;
use BatteryCatalog\Models\BatteryCatalog;
use ContractorCatalog\Models\Contractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use InverterCatalog\Models\InverterCatalog;
use PanelCatalog\Models\PanelCatalog;
use SolarPlantEquipment\Models\SolarProject;
use SolarPlantRequests\Models\SolarPlantRequest;

class SolarProjectController
{
    /** role_id of inspectors in the users table */
    private const INSPECTOR_ROLE_ID = 13;

    private function getFormData(): array
    {
        return [
            'requests'    => SolarPlantRequest::query()->latest()->get(),
            'contractors' => Contractor::query()->orderBy('company_name')->get(),
            'inspectors'  => User::query()->where('role_id', self::INSPECTOR_ROLE_ID)->orderBy('name')->get(),
        ];
    }

    public function index(): View
    {
        $projects = SolarProject::query()
            ->with(['request', 'contractor', 'inspector'])
            ->latest()
            ->paginate(15);

        return view('solar-plant-equipment::projects.index', compact('projects'));
    }

    public function create(Request $request): View
    {
        $data = $this->getFormData();

        // اگر request_id در query string بود، تقاضا را پیدا کن و فیلدها را پر کن
        if ($requestId = $request->query('request_id')) {
            $solarRequest = SolarPlantRequest::find($requestId);

            if ($solarRequest) {
                // old() را شبیه‌سازی می‌کنیم تا view فیلدها را از قبل پر نشان دهد
                session()->flashInput([
                    'request_id'    => $solarRequest->id,
                    'contractor_id' => $solarRequest->selected_contractor_id,
                    'status'        => 'in_progress',
                ]);

                $data['prefilledRequest'] = $solarRequest;
            }
        }

        return view('solar-plant-equipment::projects.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'request_id'                  => ['nullable', 'integer'],
            'contractor_id'               => ['nullable', 'integer', 'exists:contractors,id'],
            'inspector_id'                => ['nullable', 'integer', 'exists:users,id'],
            'installation_start_date'     => ['nullable', 'date'],
            'installation_end_date'       => ['nullable', 'date'],
            'commissioning_date'          => ['nullable', 'date'],
            'latitude'                    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'                   => ['nullable', 'numeric', 'between:-180,180'],
            'satba_contract_number'       => ['nullable', 'string', 'max:191'],
            'status'                      => ['nullable', 'string', 'in:' . implode(',', array_keys(SolarProject::getStatuses()))],
            'health_card_no'              => ['nullable', 'string', 'max:191'],
            'health_card_issue_date'      => ['nullable', 'date'],
            'health_card_expiry_date'     => ['nullable', 'date'],
            'description'                 => ['nullable', 'string'],
        ]);

        if (empty($validated['status'])) {
            $validated['status'] = null;
        }

        SolarProject::query()->create($validated);

        return redirect()
            ->route('solar-plant-equipment.projects.index')
            ->with('success', 'پروژه با موفقیت ثبت شد.');
    }

    public function show(SolarProject $project): View
    {
        $project->load(['installedPanels', 'installedInverters', 'installedBatteries', 'request', 'contractor', 'inspector', 'inspections', 'inspections.inspector']);

        $panels    = \PanelCatalog\Models\PanelCatalog::query()->orderBy('brand')->get();
        $inverters = \InverterCatalog\Models\InverterCatalog::query()->orderBy('brand')->get();
        $batteries = \BatteryCatalog\Models\BatteryCatalog::query()->orderBy('brand')->get();

        return view('solar-plant-equipment::projects.show', compact('project', 'panels', 'inverters', 'batteries'));
    }

    public function edit(SolarProject $project): View
    {
        return view('solar-plant-equipment::projects.edit', array_merge(
            $this->getFormData(),
            ['project' => $project]
        ));
    }

    public function update(Request $request, SolarProject $project): RedirectResponse
    {
        $validated = $request->validate([
            'request_id'                  => ['nullable', 'integer'],
            'contractor_id'               => ['nullable', 'integer', 'exists:contractors,id'],
            'inspector_id'                => ['nullable', 'integer', 'exists:users,id'],
            'installation_start_date'     => ['nullable', 'date'],
            'installation_end_date'       => ['nullable', 'date'],
            'commissioning_date'          => ['nullable', 'date'],
            'latitude'                    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'                   => ['nullable', 'numeric', 'between:-180,180'],
            'satba_contract_number'       => ['nullable', 'string', 'max:191'],
            'status'                      => ['nullable', 'string', 'in:' . implode(',', array_keys(SolarProject::getStatuses()))],
            'health_card_no'              => ['nullable', 'string', 'max:191'],
            'health_card_issue_date'      => ['nullable', 'date'],
            'health_card_expiry_date'     => ['nullable', 'date'],
            'description'                 => ['nullable', 'string'],
        ]);

        $project->update($validated);

        return redirect()
            ->route('solar-plant-equipment.projects.index')
            ->with('success', 'پروژه با موفقیت ویرایش شد.');
    }

    /**
     * تولید گواهی سلامت به صورت PDF / صفحه قابل چاپ (فقط اگر پروژه تایید شده باشد)
     */
    public function healthCertificate(SolarProject $project)
    {
        abort_if($project->status !== SolarProject::STATUS_APPROVED, 403, 'این پروژه هنوز تأیید نشده است و امکان صدور گواهی سلامت برای آن وجود ندارد.');

        $project->load([
            'request',
            'contractor',
            'inspector',
            'inspections',
            'installedPanels',
            'installedPanels.catalog',
            'installedInverters',
            'installedInverters.catalog',
            'installedBatteries',
            'installedBatteries.catalog',
        ]);

        $view = view('solar-plant-equipment::projects.health-certificate', compact('project'));

        // اگر پکیج barryvdh/laravel-dompdf نصب بود → خروجی PDF مستقیم
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view->render())
                ->setPaper('a4', 'portrait');
            $fileName = 'health-certificate-project-' . $project->id . '.pdf';
            return $pdf->stream($fileName);
        }

        // در غیر این صورت → HTML قابل چاپ (کاربر از طریق مرورگر ذخیره/چاپ می‌کند)
        return response($view->render(), 200)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }
}
