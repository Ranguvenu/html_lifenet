<?php

$addons = [
    'block_announcement' => [
        'handlers' => [
            'announcement' => [
                'delegate' => 'CoreBlockDelegate',
                'method' => 'view_announcement',
                'displaydata' => [
                    'title' => '',
                    'icon' => '',
                ],
            ],
        ],
        'lang' => [
            ['pluginname', 'block_announcement'],
        ],
    ],
];