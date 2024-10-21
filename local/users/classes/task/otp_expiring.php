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

namespace local_users\task;

use xmldb_table;
use stdClass;

/**
 * Class otp_expiring
 *
 * @package    local_users
 * @copyright  2024 Sachin <sachin.waghmare@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class otp_expiring extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('task_otpexpire', 'local_users');
    }

    /**
     * Execute the function to update the status of an OTP (One-Time Password) to expired.
     *
     */
    public function execute() {
        global $DB;
        $smssettings = get_config('local_users');
        $dbman = $DB->get_manager();
        $table = new xmldb_table('local_user_verification');
        if ($dbman->table_exists($table)) {
            $data = $DB->get_records('local_user_verification');
            if ($data) {
                foreach ($data as $otp) {
                    $con = ($otp->status == 0 && $otp->inuse == 0 && $otp->expired == 0 && $otp->lockcounter == 0);
                    $time = ($otp->timecompleted > $otp->timecreated) ? $otp->timecompleted : $otp->timecreated;

                    $update = new stdClass();
                    $update->id = $otp->id;
                    $update->timemodified = time();

                    // Checking condition to expire the verification code.
                    if ($con) {
                        if (time() > ($time + $smssettings->duration)) {
                            $update->expired = 1;
                        }
                    } // End of if condition for code expire.

                    if ($otp->status > $smssettings->lockout) {
                        if (time() > ($time + 30 * MINSECS)) {
                            $update->status = 0;
                        }
                    } // End of if condition for status reset.

                    if ($otp->lockcounter >= $smssettings->lockout) {
                        if (time() > ($time + 30 * MINSECS)) {
                            $update->lockcounter = 0;
                        }
                    }// End of if condition for lockcounter reset.

                    $DB->update_record('local_user_verification', $update);
                } // End of foreach loop.
            } // End of parent if condition.
        }
    } // End of "execute" function.
}
