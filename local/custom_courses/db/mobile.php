<?php

$addons = [
    'local_custom_courses' => [
        'handlers' => [
            'deletecourses' => [
                'delegate' => 'CoreMainMenuDelegate',
                'init' => 'deletecourses_init'
            ],
        ],
        'lang' => [
            ['pluginname', 'local_custom_courses'],
            ['courses', 'local_custom_courses'],
            ['deletecourses', 'local_custom_courses'],
        ],
    ],
];
