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
 * Renderer to get courses statistics
 *
 * @package   block_statistics
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("$CFG->libdir/completionlib.php");
use local_resources\lib;
use core_course\analytics\target\course_completion;

class block_statistics_renderer extends \plugin_renderer_base {

    /**
     * Function is used to display course statictics
     *
     * @return [string] template
     */
    public function getstatistics() {
        global $USER;

        $courses = lib::mycourses();
        $enrolledcourses = $courses['count'] ?: 0;
        $completedcourses = 0;
        foreach ($courses['hascourses'] as $key => $value) {
            $value = (object) $value;
            $ccompletion = new \completion_completion(['userid' => $USER->id, 'course' => $value->id]);
            if ($ccompletion->is_complete()) {
                $completedcourses++;
            } else {
                // empty;
            }
        }

        $inprogresscourses = $enrolledcourses - $completedcourses;

        return $this->output->render_from_template(
            'block_statistics/index', [
            'enrolled' => $enrolledcourses,
            'inprogres' => $inprogresscourses,
            'completed' => $completedcourses,
        ]);
    }
}
