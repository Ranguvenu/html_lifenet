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


namespace local_resources\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/blocklib.php');

use stdClass;
use local_resources\lib;

class mobile {

    public static function view_resources($args) {
        global $CFG, $DB, $PAGE, $OUTPUT, $USER;

        $renderer = $PAGE->get_renderer('local_resources');

        $data = new \stdClass();

        $filterdata = (object)['resources' => true];
        $stable = new \stdClass();
        $stable->start = $args->start;
        $stable->length = 10;
        $coursesdata = lib::mycourses($stable, $filterdata);
        $courses = $coursesdata['hascourses'];

        foreach ($courses as $course) {
            $resources = $renderer->get_resources($course);
            $course->resources = $resources;
            $course->hasresources = count($resources);
        }

        $data->courses = array_values($courses);

        $html = $OUTPUT->render_from_template('local_resources/mobile/latest/resources', $data);

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $html,
                ],
            ],
            'javascript' => self::template_js($args),
            'otherdata' => [
                'canLoadMore' => ($coursesdata['count'] > ($stable->start * $stable->length)),
                'total' => $coursesdata['count'],
                'courses' => json_encode(array_values($courses))
            ],            
        ];
    }

    /**
     * Return the js for template
     *
     * @return string Javascript
     */
    public static function template_js($args): string {
        global $CFG;

        $args = (object)$args;
        $foldername = $args->appversioncode >= 3950 ? 'latest/' : 'ionic3/';

        return file_get_contents($CFG->dirroot . "/local/resources/mobileapp/resources.js");
    }

}
