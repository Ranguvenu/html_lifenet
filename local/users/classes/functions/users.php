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
 * Class users functions are defined here.
 *
 * @package     local_users
 * @copyright   Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_users\functions;

require_once($CFG->dirroot.'/user/lib.php');

use html_writer;
use moodle_url;
use context_system;
use tabobject;
use user_create_user;
use context_user;
use core_user;

/**
 * Class users functions are defined here.
 *
 * @package     local_users
 * @copyright   Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class users {

    private static $_users;
    private $dbHandle;


    public static function getInstance() {
        if (!self::$_users) {
            self::$_users = new users();
        }
        return self::$_users;
    }

    /**
     * @method insert_newuser
     * @todo To create new user with system role
     * @param object $data Submitted form data
     */
    public function insert_newuser($data) {
        global $DB, $USER, $CFG;
        $userdata = (object)$data;
        foreach ($data as $key => $value) {
            $userdata->$key = trim($value);
        }

        $userdata->confirmed = 1;
        $userdata->deleted = 0;
        $userdata->mnethostid = 1;
        if (strtolower($userdata->email) != $userdata->email) {
            $userdata->email = strtolower($userdata->email);
        }
        $userdata->password = hash_internal_user_password($userdata->password);
        $createpassword = $userdata->createpassword;
        $id = user_create_user($userdata, false);
        if ($createpassword) {
            $userdata->id = $id;
            setnew_password_and_mail($userdata);
            unset_user_preference('create_password', $userdata);
            set_user_preference('auth_forcepasswordchange', 1, $userdata);

        } else if ($form_status == 0) {
            $userdata->id = $id;
            set_user_preference('auth_forcepasswordchange', $userdata->preference_auth_forcepasswordchange, $userdata);
        }
        $DB->set_field('local_user_verification', 'userid', $id, ['id' => $data->vid]);
        return $id;
    } //End of insert_newuser function.
}//End of users class.
