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
global $DB,$CFG, $USER, $OUTPUT, $PAGE;
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/blocks/announcement/lib.php');
$id = optional_param('id', 0, PARAM_INT);
$visible = optional_param('visible', -1, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
$edit = optional_param('edit', 0, PARAM_INT);
$collapse = optional_param('collapse', 0, PARAM_INT);
$courseid = 1;
require_login();
$PAGE->requires->jquery();
$PAGE->requires->css('/blocks/broadcast/css/jquery.dataTables.min.css');
$PAGE->navbar->add(get_string('dashboard', 'block_announcement'), new moodle_url('/my/index.php'));

$url = new moodle_url('/blocks/announcement/announcements.php', array('courseid' => $courseid));
$PAGE->set_url($url);
if (is_siteadmin()) {
    $title = get_string('manageanno', 'block_announcement');
} else {
    $title = get_string('pluginname', 'block_announcement');
}
$PAGE->navbar->add($title, new moodle_url('/my'));
$PAGE->set_title($title);
$PAGE->set_heading($title);

$systemcontext = context_system::instance();

if (isguestuser($USER->id)) {
   print_error('nopermission');
}

$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');
$PAGE->requires->js_call_amd('block_announcement/announcement', 'DatatablesAnnounce', []);
$renderer = $PAGE->get_renderer('block_announcement');

if ($id > 0 && $visible != -1) {
    $dataobject = new stdClass();
    $dataobject->id = $id;
    $dataobject->visible = $visible;
    $DB->update_record('block_announcement', $dataobject);
    redirect($pageurl);
}

echo $OUTPUT->header();
if ($delete > 0) {
    $announcement = $DB->get_record('block_announcement', array('id' => $delete));
    if ($announcement) {      
        if ($DB->delete_records('block_announcement', array('id' => $delete))){
            echo $OUTPUT->notification(get_string('announce_delete', 'block_announcement', $announcement->name), 'notifysuccess');
        }      
    }
}

if (is_siteadmin($USER->id) || has_capability('block/announcement:manage_announcements', $systemcontext)){
    $systemcontext = context_system::instance();

    echo "<div class='coursebackup mb-3'>   
        <a class='course_extended_menu_itemlink' title='Create Announcement' data-action='announcementmodal' onclick ='(function(e){ require(\"block_announcement/announcement\").init({selector:\"announcementmodal\", contextid:$systemcontext->id, id:0}) })(event)'><button class='btn btn-primary'>".get_string('create_announcement','block_announcement')."</button>
        </a>
    </div>";
}

echo $renderer->announcements($courseid);
echo $OUTPUT->footer();
