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
 * Admin settings and defaults
 *
 * @package auth_otp
 * @copyright  2022 Sreenivas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configtext('auth_otp/otpserviceip', get_string('auth_otpserviceip', 'auth_otp'),
            '','', PARAM_RAW));

    // $settings->add(new admin_setting_configtext('auth_otp/otpservicereportip', get_string('auth_otpservicereportip', 'auth_otp'),
    //         '','', PARAM_RAW));

    $settings->add(new admin_setting_configtext('auth_otp/apikey', get_string('auth_apikey', 'auth_otp'),
            '','', PARAM_RAW));

    $settings->add(new admin_setting_configtext('auth_otp/appusername', get_string('auth_appusername', 'auth_otp'),
            '','', PARAM_RAW));

 	$settings->add(new admin_setting_configtext('auth_otp/templateid', get_string('auth_templateid', 'auth_otp'),'','', PARAM_RAW));

    // $settings->add(new admin_setting_configtext('auth_otp/broadcastid', get_string('auth_broadcastid', 'auth_otp'),'','', PARAM_RAW));

    $settings->add(new admin_setting_configtext('auth_otp/websiteparam', get_string('auth_websiteparam', 'auth_otp'),'','', PARAM_RAW));

    $settings->add(new admin_setting_configtext('auth_otp/revokethreshold',
        get_string('revokethreshold', 'auth_otp'),
        get_string('revokethreshold_help', 'auth_otp'), 3, PARAM_INT));

    $settings->add(new class(
        'auth_otp/minrequestperiod',
        get_string('minrequestperiod', 'auth_otp'),
        get_string('minrequestperiod_help', 'auth_otp')
    ) extends admin_setting_configtext {
        public function __construct($name, $visiblename, $description) {
            $readers = get_log_manager()->get_readers('\core\log\sql_reader');
            $logreader = reset($readers);
            parent::__construct($name, $visiblename, $description, $logreader ? 300 : 0, PARAM_INT);
            if (!$logreader && !empty($this->get_setting())) {
                $this->description .= ' ' . get_string('logstorerequired', 'auth_otp',
                        (string)new moodle_url('/admin/settings.php', ['section' => 'managelogging'])
                    );
            }
        }
    });

}
