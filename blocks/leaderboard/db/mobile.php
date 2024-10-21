<?php

$addons = [
    'block_leaderboard' => [
        'handlers' => [
            'viewleaderboard' => [
                'delegate' => 'CoreBlockDelegate',
                'method' => 'view_leaderboard',
                'displaydata' => [
                    'title' => 'pluginname',
                    'type' => ''
                ],
                // 'init' => 'leaderboard_init',
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/leaderboard/mobile.css?v=2024070500',
                    'version' => 2024070500
                ],                
            ],
            'leaderboardladder' => [
                'delegate' => 'CoreBlockDelegate',
                'method' => 'leaderboardladder',
                'displaydata' => [
                    'title' => 'pluginname',
                ],
                // 'init' => 'leaderboard_init',
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/leaderboard/mobile.css?v=2024070500',
                    'version' => 2024070500
                ],                
            ],
        ],
        'lang' => [
            ['pluginname', 'block_leaderboard'],
            ['PT', 'block_leaderboard'],
            ['viewmore', 'block_leaderboard'],
        ],
    ],
];
