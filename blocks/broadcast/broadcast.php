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
 * Contact page
 *
 * @package    block_broadcast
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

use block_broadcast\api;

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_url('/blocks/broadcast/broadcast.php', ['id' => $id]);
$PAGE->set_title(get_string('pluginname','block_broadcast'));
if ($id > 0) {
    $heading = get_string('updatemessage','block_broadcast');
} else {
    $heading = get_string('addnewmessage','block_broadcast');
}
$PAGE->set_heading($heading);

$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);

$mform = new block_broadcast\forms\communication_form(null, ['id' => $id]);

if ($id > 0) {
    $data = api::broadcast_records($id);
    $data->message = ['text' => $data->message];
    $mform->set_data($data);
}

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/'));
} else if($data = $mform->get_data()) {
    $id = api::broadcast_instance($data);
    if ($id) {
        redirect(new moodle_url('/'));
    }
}

echo $OUTPUT->header();
if (!is_siteadmin()) {
    throw new \moodle_exception(get_string('nopermissions', 'block_broadcast'));
} else {
    echo $mform->display();
}
echo $OUTPUT->footer();
