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
 * @package     block_learnerscript
 * @copyright   2022 Daniel Thies <dethies@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$addons = [
    'block_learnerscript' => [
        'handlers' => [
            'enrolledlessons' => [
                'displaydata' => [
                    'class' => '',
                    'title' => 'pluginname',
                    'type' => ''
                ],
                'delegate' => 'CoreBlockDelegate',
                'init' => 'init',
                'method' => 'enrolledlessons',
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/learnerscript/mobile.css',
                    'version' => 1
                ]                
            ],
            'completedlessons' => [
                'displaydata' => [
                    'class' => '',
                    'title' => 'pluginname',
                    'type' => ''
                ],
                'delegate' => 'CoreBlockDelegate',
                'init' => 'init',
                'method' => 'completedlessons',
                'styles' => [
                    'url' => $CFG->wwwroot . '/blocks/learnerscript/mobile.css',
                    'version' => 1
                ]                
            ],
        ],
        'lang' => [ // Language strings that are used in all the handlers.
            ['pluginname', 'block_learnerscript'],
            ['enrolledlessons', 'block_learnerscript'],
            ['completedlessons', 'block_learnerscript'],
            ['viewmore', 'block_learnerscript'],
            ['totalactivities', 'block_learnerscript'],
            ['inprogressactivities', 'block_learnerscript'],
            ['completedactivities', 'block_learnerscript'],            
        ],
    ],
];
