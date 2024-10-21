<?php

$addons = [
    'block_statistics' => [
        'handlers' => [
            'statistics' => [
                'delegate' => 'CoreBlockDelegate',
                'method' => 'view_statistics',
                'displaydata' => [
                    'title' => '',
                    'icon' => '',
                ],
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/statistics/mobilecss.php?v=2024070500',
                    'version' => 2024070500
                ],                   
            ],
        ],
        'lang' => [
            ['pluginname', 'block_statistics'],
            ['enrolled', 'block_statistics'],
            ['inprogress', 'block_statistics'],
            ['completed', 'block_statistics'],            
        ],
    ],
];
