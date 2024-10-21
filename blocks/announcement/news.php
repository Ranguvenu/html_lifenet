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
use \blocks_announcement\form\announcement_form as announcement_form;
require_once($CFG->dirroot . '/blocks/announcement/lib.php');
$delete = optional_param('delete', 0, PARAM_INT);
$edit = optional_param('edit', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$back = optional_param('back', 0, PARAM_INT);
$home = optional_param('home', 0, PARAM_INT);
$courseid = 1;
require_login();
$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);

$PAGE->requires->css('/blocks/announcement/css/jquery.dataTables.css');
if (is_siteadmin()) {
    $title = get_string('manageanno', 'block_announcement');
} else {
    $title = get_string('pluginname', 'block_announcement');
}
$PAGE->navbar->add(get_string('dashboard', 'block_announcement'), new moodle_url('/my'));
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->navbar->add($title, new moodle_url('/blocks/announcement/announcements.php?collapse=0', []));
$PAGE->navbar->add(get_string('view', 'block_announcement'));

$url = new moodle_url('/blocks/announcement/announcements.php', array('courseid' => $courseid));
$PAGE->set_url($url);


if(isguestuser($USER->id)){
    print_error('nopermission');
}

$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');

echo $OUTPUT->header();

$renderer = $PAGE->get_renderer('block_announcement');
$now = time();

$params = [];
$params['id'] = $id;

$sql = "SELECT id,courseid,usermodified,name,description FROM {block_announcement} WHERE id = $id";
if (!is_siteadmin()) {
    $sql .= " AND country = :country AND enddate > :time ";
    $params['country'] = $USER->country;
    $params['time'] = $now;
}

$announcementssql = $DB->get_record_sql($sql, $params);

if ($announcementssql) {
    $data = '';
    $course = $DB->get_record('course', array('id' => $announcementssql->courseid, 'visible' => 1));
  
    $user = $DB->get_record('user', array('id' => $announcementssql->usermodified, 'confirmed' => 1, 'deleted' => 0, 'suspended' => 0));
    
    $data .= html_writer::tag('h3', $announcementssql->name, array('class' => 'createnews'));

    if ($back) {
        $url = new moodle_url('/blocks/announcement/announcements.php?collapse=0', array());
        $out = html_writer::link($url, get_string("back", "block_announcement"), array('class' => 'btn btn-primary'));
    }

    $data .= html_writer::div($out, 'delnews pull-right text-right mt-10 mb-10  p-10 mr-20 clear');
    $data .= html_writer::div(($announcementssql->description), 'addnews')."</br>";
    $return = '<input type="submit" id="submit_news"  value="Back" />';
    echo $data;
} else {
    echo get_string('no_announcements', 'block_announcement');
}               

echo $OUTPUT->footer();
