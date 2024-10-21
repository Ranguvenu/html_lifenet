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
 * External functions and service definitions for the MTN payment gateway plugin.
 *
 * @package    paygw_mpesakenya
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'paygw_mpesakenya_get_config_for_js' => [
        'classname' => \paygw_mpesakenya\external\get_config_for_js::class,
        'methodname' => 'execute',
        'description' => 'Returns the configuration settings',
        'type' => 'read',
        'ajax' => true,
    ],
    'paygw_mpesakenya_transaction_start' => [
        'classname' => \paygw_mpesakenya\external\transaction_start::class,
        'methodname' => 'execute',
        'description' => 'Returns a new transaction id',
        'type' => 'read',
        'ajax' => true,
    ],
    'paygw_mpesakenya_transaction_complete' => [
        'classname' => \paygw_mpesakenya\external\transaction_complete::class,
        'methodname' => 'execute',
        'description' => 'Finalise a MPesa Kenya payment',
        'type' => 'write',
        'ajax' => true,
    ],
];
