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
 * Renderer to get List of courses
 *
 * @package   block_lessons
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_resources\lib;

class block_lessons_renderer extends \plugin_renderer_base {

    /**
     * get_dashboardlessons
     *
     * @param  [object] $stable
     * @param  [object] $filtervalues
     * @return [array]
     */
    public function get_dashboardlessons($stable, $filterdata) {
        global $CFG;
        $courses = lib::mycourses($stable, $filterdata);
        $courses['hascourses'] = $courses['hascourses'] ?: [];

        foreach ($courses['hascourses'] as $key => $value) {
            if (file_exists($CFG->dirroot . '/blocks/lessons/lib.php')) {
                require_once($CFG->dirroot . '/blocks/lessons/lib.php');
                $courseimage = course_summary_files($value);
                if (is_object($courseimage)) {
                    $imageurl = $CFG->wwwroot . '/blocks/lessons/pix/01.png';
                } else {
                    $imageurl = $courseimage;
                }
            }
            $value->image = $imageurl;
        }

        return $courses;
    }

    /**
     * get_lessons
     *
     * @param  mixed $filter
     * @return void
     */
    public function get_lessons($filter = false) {
        global $USER, $DB, $OUTPUT;
        
        $systemcontext = \context_system::instance();

        $options = array(
            'targetID' => 'courses',
            'perPage' => 8,
            'cardClass' => 'col-lg-3 col-md-4 col-12 mb-5',
            'viewType' => 'card',
        );
        $options['methodName'] = 'block_lessons';
        $options['templateName'] = 'block_lessons/dashboardcourses';
        $options = json_encode($options);
        $filterdata = json_encode(array());
        $dataoptions = json_encode(array('contextid' => $systemcontext->id));

        $context = [
            'targetID' => 'courses',
            'options' => $options,
            'dataoptions' => $dataoptions,
            'filterdata' => $filterdata,
        ];

        if ($filter) {
            return $context;
        } else {
            return $this->render_from_template('block_lessons/cardPaginate', $context);
        }
    }
}
