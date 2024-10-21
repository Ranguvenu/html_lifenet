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

/**
 * services to get List of lessons
 *
 * @package   block_lessons
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$functions = [
    'block_lessons' => [
        'classname' => 'block_lessons_external',
        'methodname' => 'getlessons',
        'classpath'   => 'blocks/lessons/classes/external.php',
        'description' => 'View all getlessons',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => false,
    ],
    'block_lessons_get_lessons' => [
        'classname' => 'block_lessons_external',
        'methodname' => 'get_lessons',
        'classpath'   => 'blocks/lessons/classes/external.php',
        'description' => 'View all courses',
        'type' => 'read',
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE]
    ],       
];
