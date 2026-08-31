<?php

return [
    'menu' =>[

        'dashboard' => [
            'icon' => 'dashboard',
            'fa_name' => 'صفحه نخست',
            'route-name' => 'admin.dashboard',
            'submenu' => [
                'dashboard' => [ 'fa_name' => 'داشبرد', 'route-name' => '', 'route-url' => 'admin' ],
            ]
        ],
        'workflow-inbox' => [
            'icon' => 'inbox',
            'fa_name' => 'درخواست های تکمیل نشده',
            'route-name' => 'simpleWorkflow.inbox.index',
            'submenu' => [
                'new-process' => [ 'fa_name' => 'فرایند جدید', 'route-name' => 'simpleWorkflow.process.startListView' ],
                'inbox' => [ 'fa_name' => 'کارتابل', 'route-name' => 'simpleWorkflow.inbox.index' ],
                'done-inbox' => [ 'fa_name' => 'انجام شده ها', 'route-name' => 'simpleWorkflow.inbox.done' ],
            ]
        ],
        'categorized-inbox' => [
            'icon' => 'inbox',
            'fa_name' => 'کارتابل',
            'route-name' => 'simpleWorkflow.inbox.categorized',
            'submenu' => [
                'inbox' => [ 'fa_name' => 'کارتابل', 'route-name' => 'simpleWorkflow.inbox.index' ],
                'done-inbox' => [ 'fa_name' => 'انجام شده ها', 'route-name' => 'simpleWorkflow.inbox.done' ],
            ]
        ],
        'my-request' => [
            'icon' => 'inbox',
            'fa_name' => 'درخواست های من',
            'route-name' => 'simpleWorkflowReport.my-request.index',
            'submenu' => [
                'new-process' => [ 'fa_name' => 'فرایند جدید', 'route-name' => 'simpleWorkflow.process.startListView' ],
                'inbox' => [ 'fa_name' => 'کارتابل', 'route-name' => 'simpleWorkflow.inbox.index' ],
                'done-inbox' => [ 'fa_name' => 'انجام شده ها', 'route-name' => 'simpleWorkflow.inbox.done' ],
            ]
        ],
        'workflow-report' => [
            'icon' => 'report',
            'fa_name' => 'گزارشات',
            'route-name' => 'simpleWorkflowReport.index',
            'submenu' => [
                'all-requests' => [ 'fa_name' => 'کل درخواست ها', 'route-name' => 'simpleWorkflowReport.all-requests.index' ],
                'summary' => [ 'fa_name' => 'خلاصه', 'route-name' => 'simpleWorkflowReport.summary-report.index' ],
                'my-request' => [ 'fa_name' => 'درخواست‌های من', 'route-name' => 'simpleWorkflowReport.my-request.index' ],
                'role-form-control' => [ 'fa_name' => 'فرم گزارش نقش ها', 'route-name' => 'simpleWorkflowReport.role.index' ],
            ]
        ],
        'workflow' => [
            'icon' => 'account_tree',
            'fa_name' => 'گردش کار',

            'submenu' => [
                'process' => [ 'fa_name' => 'فرایند', 'route-name' => 'simpleWorkflow.process.index' ],
                'task-actors' => [ 'fa_name' => 'تسک ها', 'route-name' => 'simpleWorkflow.task-actors.index' ],
                'forms' => [ 'fa_name' => 'فرم ها', 'route-name' => 'simpleWorkflow.form.index'  ],
                'scripts' => [ 'fa_name' => 'اسکریپت ها', 'route-name' => 'simpleWorkflow.scripts.index' ],
                'conditions' => [ 'fa_name' => 'شرط ها', 'route-name' => 'simpleWorkflow.conditions.index' ],
                'fields' => [ 'fa_name' => 'فیلدها', 'route-name' => 'simpleWorkflow.fields.index' ],
                'entities' => [ 'fa_name' => 'موجودیت ها', 'route-name' => 'simpleWorkflow.entities.index' ],
                'view-models' => [ 'fa_name' => 'ویو مدل ها', 'route-name' => 'simpleWorkflow.view-model.index' ],
                'all-inbox' => [ 'fa_name' => 'کارتابل همه', 'route-name' => 'simpleWorkflow.inbox.cases.list' ],
            ]
        ],
        'voip' => [
            'icon' => 'phone',
            'fa_name' => 'تلفن',
            'route-name' => 'simpleWorkflowReport.index',
            'submenu' => [
                'settings' => [ 'fa_name' => 'تنظیمات', 'route-name' => 'ami.settings' ],
                'ext-status' => [ 'fa_name' => 'وضعیت داخلی ها', 'route-name' => 'ami.status' ],
            ]
        ],
        'translations' => [
            'icon' => 'language',
            'fa_name' => 'ترجمه',
            'route-url' => '/translations',
            'submenu' => [
                'index' => [ 'fa_name' => 'ترجمه', 'route-name' => '', 'route-url' => '/translations' ],
            ]
        ],
        'users' => [
            'icon' => 'person',
            'fa_name' => 'کاربران',
            'route-url' => 'user/all',
            'submenu' => [
                'dashboard' => [ 'fa_name' => 'همه', 'route-name' => '', 'route-url' => 'user/all' ],
                'role' => [ 'fa_name' => 'نقش ها', 'route-name' => 'role.listForm', 'route-url' => '' ],
                'method' => [ 'fa_name' => 'متد ها', 'route-name' => 'method.list', 'route-url' => '' ],
                'department' => [ 'fa_name' => 'دپارتمان ها', 'route-name' => 'department.index', 'route-url' => '' ],
            ]
        ],

        'panel-catalog' => [
            'icon' => 'solar_power',
            'fa_name' => 'کاتالوگ پنل',
            'route-name' => 'panel-catalog.index',
            'submenu' => [
                'index' => [ 'fa_name' => 'لیست پنل‌ها', 'route-name' => 'panel-catalog.index' ],
                'create' => [ 'fa_name' => 'افزودن پنل جدید', 'route-name' => 'panel-catalog.create' ],
            ]
        ],
        'expert-catalog' => [
            'icon' => 'manage_accounts',
            'fa_name' => 'کاتالوگ کارشناسان',
            'route-name' => 'expert-catalog.index',
            'submenu' => [
                'index'  => [ 'fa_name' => 'لیست کارشناسان', 'route-name' => 'expert-catalog.index' ],
                'create' => [ 'fa_name' => 'افزودن کارشناس جدید', 'route-name' => 'expert-catalog.create' ],
            ]
        ],
        'request-expert-review-admin' => [
            'icon' => 'assignment_ind',
            'fa_name' => 'تخصیص کارشناس',
            'route-name' => 'request-expert-review.admin.index',
            'submenu' => [
                'index' => [ 'fa_name' => 'تخصیص کارشناس به تقاضاها', 'route-name' => 'request-expert-review.admin.index' ],
            ]
        ],
        'request-expert-review-expert' => [
            'icon' => 'assignment',
            'fa_name' => 'تقاضاهای من (کارشناس)',
            'route-name' => 'request-expert-review.expert.index',
            'submenu' => [
                'index' => [ 'fa_name' => 'تقاضاهای اختصاص‌یافته', 'route-name' => 'request-expert-review.expert.index' ],
            ]
        ],
        'expert-initial-visit' => [
            'icon' => 'fact_check',
            'fa_name' => 'بازدید اولیه',
            'route-name' => 'expert-initial-visit.index',
            'submenu' => [
                'index' => [ 'fa_name' => 'لیست گزارش‌های بازدید', 'route-name' => 'expert-initial-visit.index' ],
            ]
        ],
        'exit' => [
            'icon' => 'logout',
            'fa_name' => 'خروج',
            'route-name' => 'logout',
            'submenu' => [
                'create' => [ 'fa_name' => 'ایجاد', 'route-name' => 'ATRoutes.index', 'route-url' => '' ],
                'show' => [ 'fa_name' => 'مشاهده', 'route-name' => 'ATRoutes.show.listForm', 'route-url' => '' ],
            ]
        ],

    ]
];
