<?php
/**
 * This file is part of eAbyas
 *
 * Copyright eAbyas Info Solutons Pvt Ltd, India
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author eabyas  <info@eabyas.in>
 * @package ODL
 * @subpackage blocks_announcement
 */

function block_announcement_output_fragment_announcement_form($args){
    global $DB, $CFG, $PAGE;

    $args = (object) $args;
    $context = $args->context;
    $id = $args->id;
    
    $o = '';
    $formdata = [];
    if (!empty($args->jsonformdata) && $args->jsonformdata != '{}') {
        $serialiseddata = json_decode($args->jsonformdata);
        parse_str($serialiseddata, $formdata);
    }

    $context = context_system::instance();
	$itemid = 0;

    if ($id > 0) {
        $heading = 'Update Announcement';
        $collapse = false;
        $data = $DB->get_record('block_announcement', ['id' => $id]);
        $data->categoryid = $data->categoryid;
        $description = $data->description;
        $data->description = [];
        $data->description['text'] = $description;
    }

    $params = [
        'id' => $id,
        'context' => $context,
        'itemid' => $itemid,
        'attachment' => $data->attachment,
    ];
 
    $mform = new block_announcement\form\announcement_form(null, $params, 'post', '', null, true, $formdata);
    // Used to set the courseid.
    $mform->set_data($data);

    if (!empty($args->jsonformdata)) {
        // If we were passed non-empty form data we want the mform to call validation functions and show errors.
        $mform->is_validated();
    }
 
    ob_start();
    $mform->display();
    $o .= ob_get_contents();
    ob_end_clean();
 
    return $o;
}

function block_announcement_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $CFG;

    if ($filearea == 'announcement') {
        $itemid = (int) array_shift($args);

        $fs = get_file_storage();
        $filename = array_pop($args);
        if (empty($args)) {
            $filepath = '/';
        } else {
            $filepath = '/' . implode('/', $args) . '/';
        }

        $file = $fs->get_file($context->id, 'block_announcement', $filearea, $itemid, $filepath, $filename);

        if (!$file) {
            return false;
        }
        $filedata = $file->resize_image(200, 200);
        \core\session\manager::write_close();
        send_stored_file($file, null, 0, 1);
    }

    send_file_not_found();
}

function block_announcement_before_http_headers() {
    global $PAGE, $USER;
    if (isloggedin() && !is_siteadmin() && $PAGE->pagelayout == 'mydashboard') {
        $message = todays_announcement();
        $link = html_writer::div("<a href='".new moodle_url('/blocks/announcement/news.php', [
            'id' => $message->id,
            'back' => 0]
        ) . "'>" . strip_tags(substr($message->description, 0, 341)) . "</a>");
        if ($message->description > substr(($message->description), 0, 341)) {
            $data = [$link];
        } else {
            $data = [strip_tags($message->description)];
        }

        if ($message) {
            $PAGE->requires->js_call_amd('block_announcement/message', 'init', $data);
        }
    }
}

function todays_announcement() {
    global $DB, $USER;
    $time = strtotime(date('d-m-Y', time()));
    $sql = "SELECT *
              FROM {block_announcement}
             WHERE ($time BETWEEN startdate AND enddate) AND country = ?";
    $todayannouncement = $DB->get_record_sql($sql, [$USER->country]);

    if ($todayannouncement) {
        return $todayannouncement;
    } else {
        return null;
    }
}
