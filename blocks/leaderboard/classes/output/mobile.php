<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


namespace block_leaderboard\output;

defined('MOODLE_INTERNAL') || die();

use stdClass;

class mobile {

    public static function view_leaderboard(array $args): array {
        global $CFG, $OUTPUT, $PAGE;
        $args = (object) $args;

        $foldername = $args->appversioncode >= 3950 ? 'latest/' : 'ionic3/';

        $lib = new \block_leaderboard\lib();

        $start = 0;
        $limit = 5;

        $users = $lib->getleadreboard($limit);
        $hasusers = count($users) ? true : false;

        return [
            'templates' => [
                [
                    'id' => 'leaderboard',
                    'html' => $OUTPUT->render_from_template('block_leaderboard/mobile/' . $foldername . 'leaderboard', ['users' => array_values($users), 'hasusers' => $hasusers])
                ]
            ],
            'otherdata' => [
            ]
        ];
    }

    public static function leaderboard_init(array $args): array {
        global $CFG;
        return [
            'templates' => [],
            'javascript' => file_get_contents($CFG->dirroot . '/blocks/leaderboard/appjs/init.js'),
            'otherdata' => '',
            'files' => []
        ];
    }

    public static function leaderboardladder(array $args) {
        global $CFG, $OUTPUT, $PAGE;
        $args = (object) $args;

        $foldername = $args->appversioncode >= 3950 ? 'latest/' : 'ionic3/';

        $lib = new \block_leaderboard\lib();

        $start = 0;
        $limit = 0;
        $users = $lib->getleadreboard($limit);
        $hasusers = count($users) ? true : false;
        return [
            'templates' => [
                [
                    'id' => 'leaderboardladder',
                    'html' => $OUTPUT->render_from_template('block_leaderboard/mobile/' . $foldername . 'leaderboardladder', ['users' => array_values($users), 'hasusers' => $hasusers])
                ]
            ],
            'otherdata' => [
            ]
        ];

    }
}
