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


namespace local_custom_courses\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/blocklib.php');
require_once($CFG->dirroot . '/admin/tool/mobile/lib.php');

use stdClass;

class mobile {

    public static function deletecourses($args) {
        global $CFG, $DB, $PAGE, $OUTPUT, $USER;

        $data = new \stdClass();

        $enrolledCourses = enrol_get_my_courses();

        $data->courses = array_values($enrolledCourses);

        // print_r($data);

        $html = $OUTPUT->render_from_template('local_custom_courses/mobile/latest/courses', $data);

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => '<div>'.$html.'</div>',
                ],
            ],
            // 'javascript' => self::template_js(),
            'otherdata' => [
                // 'contextid' => $data->contextid,
            ],
        ];
    }

    /**
     * Return the js for template
     *
     * @return string Javascript
     */
    public static function template_js(): string {
        global $CFG;
        // return file_get_contents($CFG->dirroot . "/blocks/courselibrary/mobileapp/courselibrary.js");
    }

    public static function deletecourses_init(array $args) : array {
        global $CFG;
        return [
                'templates' => [],
                'javascript' => file_get_contents($CFG->dirroot . '/local/custom_courses/appjs/init.js'),
                'otherdata' => '',
                'files' => []
        ];
    }
}
