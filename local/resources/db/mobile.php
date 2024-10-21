<?php

$addons = [
    'local_resources' => [
        'handlers' => [
            'deletecourses' => [
                'delegate' => 'CoreMainMenuDelegate',
                'method' => 'view_resources',
                'displaydata' => [
                    'title' => 'resources',
                    'icon' => 'fa-book',
                ],
            ],
        ],
        'lang' => [
            ['pluginname', 'local_resources'],
            ['resources', 'local_resources'],
            ['noresourcesavavailable', 'local_resources'],
        ],
    ],
];
