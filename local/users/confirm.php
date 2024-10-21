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
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_users
 * @copyright   2024 Moodle India Information Solutions Pvt Ltd
 * @author      2024 Shamala <shamala.kandula@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__ . '/../../config.php');

global $PAGE, $USER, $OUTPUT;

$r = optional_param('r', 0, PARAM_INT);

// Systemcontest defining.
$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);
$PAGE->set_url('/local/users/confirm.php');
$PAGE->set_title(get_string('confirmed', 'local_users'));
$PAGE->set_pagelayout('frontpage');

if (isloggedin()) {
    redirect($CFG->wwwroot.'/my');
}

echo $OUTPUT->header();
$url = new moodle_url('/login/index.php');
if ($r > 0) {
	$string = get_string('confirmedmsg', 'local_users', $url);
} else {
	$string = get_string('alreadyactivatedmsg', 'local_users', $url);
}
echo $OUTPUT->render_from_template('local_users/confirmed', ['message' => $string]);
echo $OUTPUT->footer();
