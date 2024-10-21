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
 * User verification.
 *
 * @package   local_users
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

global $OUTPUT, $DB, $CFG, $PAGE;

$PAGE->set_url('/local/users/verification.php', []);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('registrationtitle', 'local_users'));
$PAGE->set_pagelayout('frontpage');

$PAGE->requires->js_call_amd('local_users/verification');

// If wantsurl is empty or /login/verification.php, override wanted URL.
// We do not want to end up here again if user clicks "Login".

$mform = new local_users\forms\verification_form(null, []);

if (isloggedin()) {
    redirect($CFG->wwwroot.'/my');
}

echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();
