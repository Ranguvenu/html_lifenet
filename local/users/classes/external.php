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
 * External file for List of projects
 *
 * @package   local_users
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

use local_users\api;
use stdClass;

require_once("$CFG->libdir/externallib.php");
require_once("$CFG->dirroot/user/lib.php");


class local_users_external extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters.
     */
    public static function validateuserdetails_parameters() {
        return new external_function_parameters(
            [               
                'phone1' => new external_value(PARAM_INT, 'phone1', false),
                'country' => new external_value(PARAM_RAW, 'otpcode', false),
                'otpcode' => new external_value(PARAM_INT, 'otpcode', VALUE_OPTIONAL),
                'flag' => new external_value(PARAM_INT, 'flag', false),
            ]
        );
    }

    /**
     * Gets the list of users based on the login user
     *
     * @param int $id user id
     * @param int $approvesstatus
     * @return string 
     */
    public static function validateuserdetails($phone1, $country, $otpcode, $flag) {
        global $DB;

        // Parameter validation.
        $params = self::validate_parameters(
            self::validateuserdetails_parameters(),
            [
                'phone1' => $phone1,
                'country' => $country,
                'otpcode' => $otpcode,
                'flag' => $flag,
            ]
        );

        $data = new stdClass();
        $data->phone1 = $phone1;
        $data->country = $country;
        $data->flag = $flag;

        $sql = "SELECT *
                  FROM {local_user_verification}
                 WHERE phonenumber = ? AND country = ? ORDER BY id DESC LIMIT 1";
        $exists = $DB->get_record_sql($sql, [$phone1, $country]);
        if ($exists) {
            if ($exists->userid == 0) {
                if (($flag == 2 || $flag == 1) && $exists->expired == 1 && $exists->inuse == 0) {
                    $response = api::insert_user_instance($data);
                } else if (($flag == 2 || $flag == 1) &&
                    $exists->inuse == 0 && $exists->expired == 0
                ) {
                    $data->id = $exists->id;
                    $data->status = $exists->status;
                    $response = api::update_user_instance($data);
                } else if ($flag == 3) {
                    $data->id = $exists->id;
                    $data->otpcode = $otpcode;
                    $response = api::validate_verificationcode($data);
                }
            } else {
                if ($exists->userid == 0 && $exists->inuse == 1) {
                    $response = ['id' => $exists->id, 'res' => 2];
                } else {
                    $response = ['id' => '', 'res' => 5];
                }
            }
        } else {
            $response = api::insert_user_instance($data);
        }

        return $response;
    }
    /**
     * Returns description of method parameters.
     *
     * @return external_value.
     */
    public static function validateuserdetails_returns() {
        // return new external_value(PARAM_INT, 'return');
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_RAW, 'id || conter'),
                'res' => new external_value(PARAM_INT, 'return res'),
            ]
        );
    }

}
