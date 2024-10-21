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
 * We defined the web service functions to install.
 * 
 * @author eabyas  <info@eabyas.in>
 * @package BizLMS
 * @subpackage blocks_announcement
 */

defined('MOODLE_INTERNAL') || die;
$functions = [
    'block_announcement_submit_create_announcement_form' => [
        'classname'   => 'block_announcement_external',
        'methodname'  => 'submit_create_announcement_form',
        'classpath'   => 'blocks/announcement/classes/external.php',
        'description' => 'Submit form',
        'type'        => 'write',
        'ajax'        => true,
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'block_announcement_announcements' => [
        'classname'   => 'block_announcement_external',
        'methodname'  => 'announcements',
        'classpath'   => 'blocks/announcement/classes/external.php',
        'description' => 'announcements',
        'type'        => 'read',
        'ajax'        => true,
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
];

