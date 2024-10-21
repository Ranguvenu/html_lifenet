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
 * User registration.
 *
 * @package   local_users
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

global $OUTPUT, $DB, $CFG, $PAGE;

$vid = required_param('vid', PARAM_INT);
$success = optional_param('success', 0, PARAM_INT);
$status = optional_param('status', 0, PARAM_INT);

$PAGE->set_url('/local/users/registration.php', ['success' => $success, 'status' => $status, 'vid' => $vid]);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('registrationtitle', 'local_users'));
$PAGE->set_pagelayout('frontpage');

// If wantsurl is empty or /login/signup.php, override wanted URL.
// We do not want to end up here again if user clicks "Login".
$userslib = new local_users\functions\users();
$mform = new local_users\forms\registration_form(null, ['success' => $success, 'status' => $status, 'vid' => $vid]);

if (isloggedin()) {
    redirect($CFG->wwwroot.'/my');
}

if ($mform->is_cancelled()) {
    redirect(get_login_url());

} else if ($user = $mform->get_data()) {
    $res = $userslib->insert_newuser($user);
    if ($res) {
        $baseurl = new moodle_url('/local/users/confirm.php?r=1');
        redirect($baseurl);
        exit;
    }
}

echo $OUTPUT->header();
    if ($vid) {
        $mform->display();
    }
echo $OUTPUT->footer();
