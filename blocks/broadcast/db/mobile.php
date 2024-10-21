<?php

$addons = [
    'block_broadcast' => [
        'handlers' => [
            'motivationspeech' => [
                'delegate' => 'CoreBlockDelegate',
                'method' => 'view_motivation',
                'displaydata' => [
                    'title' => 'pluginname',
                    'icon' => '',
                    'type' => 'template'
                ],
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/broadcast/mobilecss.php?v=2024070800',
                    'version' => 2024070800
                ], 
            ],
        ],
        'lang' => [
            ['pluginname', 'block_broadcast'],
        ],
    ],
];
