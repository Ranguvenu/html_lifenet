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
 * services to get List of courses
 *
 * @package   local_resources
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$functions = [
    'local_resources_get_courses' => [
        'classname' => 'local_resources_external',
        'methodname' => 'get_courses',
        'classpath'   => 'local/resources/classes/external.php',
        'description' => 'View all courses',
        'type' => 'read',
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE]
    ],       
];
