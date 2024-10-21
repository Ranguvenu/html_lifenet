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

namespace local_users;

use stdClass;
use local_users\functions\lib;

/**
 * Class api
 *
 * @package    local_users
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class api {

    /**
     * Insert user details
     *
     * @param [object] $data
     * @return [int]
     */
    public static function insert_user_instance($data) {
        global $DB, $USER;
        try {
            $phonecodes = lib::get_phonecodes();
            $uniqcode = random_int(100000, 999999);
            $data->otpcode = $uniqcode;
            $data->phonenumber = $data->phone1;
            $data->timecreated = time();
            $DB->insert_record('local_user_verification', $data);
            return ['id' => '', 'res' => 1];
        } catch (\moodle_exception $e) {
            print_r($e);
        }
    }

    /**
     * Update user details
     *
     * @param [object] $data
     * @return [int]
     */
    public static function update_user_instance($data) {
        global $DB, $USER;
        try {
            $lockout = get_config('local_users', 'lockout');
            $phonecodes = lib::get_phonecodes();
            $uniqcode = random_int(100000, 999999);
            $data->status = $data->status + 1;
            $data->otpcode = $uniqcode;
            $data->timemodified = time();
            if ($data->status > $lockout) {
                return ['id' => '', 'res' => 6];
            } else {
                $DB->update_record('local_user_verification', $data);
                return ['id' => '', 'res' => 1];
            }
        } catch (\moodle_exception $e) {
            print_r($e);
        }
    }

    /**
     * Validate OTP.
     *
     * @param [object] $data
     * @return [int]
     */
    public static function validate_verificationcode($data) {
        global $DB, $USER;
        try {
            $record = $DB->get_record('local_user_verification', ['id' => $data->id]);

            $error = false;
            if ($data->otpcode != $record->otpcode) {
                // Update record in DB.
                $lockcounter = $record->lockcounter + 1;
                $counter = self::get_remaining_attempts($lockcounter);
                $DB->set_field('local_user_verification', 'lockcounter', $lockcounter, ['id' => $record->id]);
                $error = true;
                return ['id' => $counter, 'res' => 3];
            }

            if ($record->expired == 1 && $record->status == 0) {
                $error = true;
                return ['id' => '', 'res' => 4];
            }

            if (!$error) {
                $update = new \stdClass();
                $update->id = $record->id;
                $update->inuse = 1;
                $update->timemodified = time();
                $update->timeverified = time();
                $DB->update_record('local_user_verification', $update);
                return ['id' => $record->id, 'res' => 2];
            }
        } catch (\moodle_exception $e) {
            print_r($e);
        }
    }

    /**
     * Return the number of remaining attempts.
     *
     * @return int the number of attempts remaining.
     */
    public static function get_remaining_attempts($counter): int {
        $lockthreshold = get_config('local_users', 'lockout');
        if ($counter === -1) {
            // If upgrade.php hasnt been run yet, just return 3.
            return $lockthreshold;
        } else {
            $count = $lockthreshold - $counter; 
            if ($count < 0) {
                return 0;
            } else {
                return $count;
            }
        }
    }
}