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
 * Course Resources.
 *
 * @package   local_resources
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

require_login();

// Print the page header.
$systemcontext = \context_system::instance();
$url = new moodle_url('/local/resources/index.php', []);
$heading = get_string('pluginname', 'local_resources');
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);

// Set the page heading and title.
$PAGE->set_heading($heading);
$PAGE->set_title($heading);

$PAGE->requires->js_call_amd('local_resources/resources', 'init', []);
$PAGE->requires->css('/local/resources/css/jquery.dataTables.min.css');

// Get the renderer.
$renderer = $PAGE->get_renderer('local_resources');

echo $OUTPUT->header();
echo $renderer->get_course_resources();
echo $OUTPUT->footer();
