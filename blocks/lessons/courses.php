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
 * List the tool provided in a course
 *
 * @package    block_lessons
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/lessons/courses.php');
$PAGE->set_heading(get_string('pluginname', 'block_lessons'));
$PAGE->set_title(get_string('pluginname', 'block_lessons'));

echo $OUTPUT->header();

$renderer = $PAGE->get_renderer('block_lessons');
$filterparams = $renderer->get_lessons(true);

echo $OUTPUT->render_from_template('block_lessons/global_filter', $filterparams);

echo $renderer->get_lessons(false);

echo $OUTPUT->footer();