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
 * Local Users external functions and service definitions.
 *
 * @package     local_custom_courses
 * @copyright   2024 Moodle India Information Solutions Pvt Ltd
 * @author      2024 Shamala <shamala.kandula@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
$functions = [   
    'local_custom_courses_form_option_selector' => [
            'classname'   => 'local_custom_courses_external',
            'methodname'  => 'form_option_selector',
            'classpath'   => 'local/custom_courses/classes/external.php',
            'description' => 'Get dynamic form options',
            'type'        => 'read',
            'loginrequired' => false,
            'ajax' => true,
        ],   
];
