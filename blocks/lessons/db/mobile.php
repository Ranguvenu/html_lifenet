<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Mobie addon definition
 *
 * @package     block_lessons
 * @copyright   2022 Daniel Thies <dethies@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$addons = [
    'block_lessons' => [
        'handlers' => [
            'viewlessons' => [
                'displaydata' => [
                    'class' => '',
                    'title' => 'pluginname',
                    'type' => ''
                ],
                'delegate' => 'CoreBlockDelegate',
                'init' => 'init',
                'method' => 'viewlessons',
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/lessons/mobile.css',
                    'version' => 1
                ]                
            ],
            'alllessons' => [
                'displaydata' => [
                    'class' => '',
                    'title' => 'pluginname',
                    'type' => ''
                ],
                'delegate' => 'CoreBlockDelegate',
                'init' => 'init',
                'method' => 'alllessons',
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/lessons/mobile.css',
                    'version' => 1
                ]                
            ],
        ],
        'lang' => [ // Language strings that are used in all the handlers.
            ['pluginname', 'block_lessons'],
            ['lessonsnotavailable', 'block_lessons'],
            ['viewmore', 'block_lessons']
        ],
    ],
];
