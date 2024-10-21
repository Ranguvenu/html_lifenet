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
 * lib file for List of projects
 *
 * @package   block_lessons
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * To get the list of projects.
 * @param $stable for start limit and end limit.
 * @param $filtervalues user search values.
 * @return array of data to the external function.
 */
function get_lessons($stable, $filtervalues) {
    global $DB, $CFG, $PAGE;

    $renderer = $PAGE->get_renderer('block_lessons');
    $courses = $renderer->get_dashboardlessons($stable, $filtervalues);
    return $courses;
}

/**
 * Function is used to get course image.
 *
 * @param [object] $course
 * @return moodle_url
 */
function course_summary_files($course) {
    global $DB, $CFG, $OUTPUT;
    if ($course instanceof stdClass) {
        $course = new core_course_list_element($course);
    }
    
    // set default course image
    foreach ($course->get_course_overviewfiles() as $file) {
        $isimage = $file->is_valid_image();
        if ($isimage) {
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/' . $file->get_contextid() . '/' .
                $file->get_component() . '/' .$file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);
        } else {
            $url = $OUTPUT->image_url('courseimg', 'block_lessons');//send_file_not_found();
        }
    }

    if (empty($url)) {
        $url = $OUTPUT->image_url('courseimg', 'block_lessons');//send_file_not_found();
    }

    return $url;
}
