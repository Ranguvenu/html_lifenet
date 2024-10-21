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
 * Settings
 *
 * @package     local_users
 * @author      Peter Burnett <peterburnett@catalyst-au.net>
 * @copyright   Catalyst IT
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG, $OUTPUT;

$users = new admin_category('local_users', new lang_string('pluginname', 'local_users'), false);

$settings = new admin_settingpage(
    'local_users',
    get_string('users:registration', 'local_users')
);
$ADMIN->add('localplugins', $settings);

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configduration('local_users/duration',
        get_string('settings:duration', 'local_users'),
        get_string('settings:duration_help', 'local_users'), 30 * MINSECS, MINSECS));

    $settings->add(new admin_setting_configtext('local_users/lockout',
        get_string('settings:lockout', 'local_users'),
        get_string('settings:lockout_help', 'local_users'), 3, PARAM_INT));

    $settings->add(new \admin_setting_configtext('local_users/api_key',
        get_string('settings:smsapi:key', 'local_users'),
        get_string('settings:smsapi:key_help', 'local_users'), ''));

    $settings->add(new \admin_setting_configpasswordunmask('local_users/api_secret',
        get_string('settings:smsapi:secret', 'local_users'),
        get_string('settings:smsapi:secret_help', 'local_users'), ''));
}
