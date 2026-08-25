<?php

return [
    'user' => [
        ['type' => 'heading', 'name' => 'Main Menu'],
        [
            'name' => 'Dashboard',
            'icon' => 'layout-dashboard',
            'url' => '/user/dashboard',
            'active' => 'user.dashboard',
        ],
        ['type' => 'heading', 'name' => 'Career Tools'],
        [
            'name' => 'My CV',
            'icon' => 'file-cv',
            'url' => '/user/cv',
            'active' => 'user.cv.index',
        ],
        [
            'name' => 'Upgrade CV',
            'icon' => 'sparkles',
            'url' => '/user/cv-upgrade',
            'active' => 'user.cv-upgrade*',
        ],
        [
            'name' => 'Job Matches',
            'icon' => 'briefcase',
            'url' => '/user/jobs',
            'active' => 'user.jobs*',
        ],
        [
            'name' => 'Skill Gaps',
            'icon' => 'chart-bar',
            'url' => '/user/skill-gaps',
            'active' => 'user.skill-gaps*',
        ],
        [
            'name' => 'Courses',
            'icon' => 'school',
            'url' => '/user/courses',
            'active' => 'user.courses*',
        ],
        [
            'name' => 'Subscription',
            'icon' => 'crown',
            'url' => '/user/subscription',
            'active' => 'user.subscription*',
        ],
        ['type' => 'heading', 'name' => 'Account'],
        [
            'name' => 'Settings',
            'icon' => 'settings',
            'url' => '/profile',
            'active' => 'profile.edit',
        ],
        [
            'name' => 'Back to Website',
            'icon' => 'home-2',
            'url' => '/',
            'active' => '',
        ],
    ],

    'admin' => [
        ['type' => 'heading', 'name' => 'Main Menu'],
        [
            'name' => 'Dashboard',
            'icon' => 'layout-dashboard',
            'url' => '/admin/dashboard',
            'active' => 'admin.dashboard',
        ],
        ['type' => 'heading', 'name' => 'Management'],
        [
            'name' => 'Users',
            'icon' => 'users',
            'url' => '/admin/users',
            'active' => 'admin.users*',
        ],
        ['type' => 'heading', 'name' => 'System'],
        [
            'name' => 'Settings',
            'icon' => 'settings',
            'url' => '/admin/settings',
            'active' => 'admin.settings',
        ],
        [
            'name' => 'Back to Website',
            'icon' => 'home-2',
            'url' => '/',
            'active' => '',
        ],
    ],
];
