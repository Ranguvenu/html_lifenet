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
 * External file
 *
 * @package   local_resources
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once $CFG->libdir . '/externallib.php';

use local_resources\lib as lib;

class local_resources_external extends external_api
{

    /**
     * Describes the parameters for recommended_courses_view webservice.
     * @return external_function_parameters
     */
    public static function get_courses_parameters()
    {
        return new external_function_parameters([
            'search' => new external_value(PARAM_RAW, 'search', VALUE_DEFAULT, ''),
            'page' => new external_value(PARAM_INT, 'Page', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * Describes the data for listofprojects webservice.
     *
     * @param array $options
     * @param array $dataoptions
     * @param int $offset
     * @param int $limit
     * @param int $contextid
     * @param array $filterdata
     * @return external_function data.
     */
    public static function get_courses(
        $search,
        $page
    ) {
        global $OUTPUT, $CFG, $DB, $USER, $PAGE;

        $params = self::validate_parameters(
            self::get_courses_parameters(),
            [
                'search' => $search,
                'page' => $page,
            ]
        );
        $limit = 10;
        $start = $page * $limit;

        $renderer = $PAGE->get_renderer('local_resources');

        $data = new \stdClass();

        $filterdata = (object)['resources' => true, 'search_query' => $search];
        $stable = new \stdClass();
        $stable->start = $start;
        $stable->length = $limit;
        $stable->search_query = $search;
        $coursesdata = lib::mycourses($stable, $filterdata);
        $courses = $coursesdata['hascourses'];

        foreach ($courses as $course) {
            $resources = $renderer->get_resources($course);
            $course->resources = $resources;
            $course->hasresources = count($resources);
        }
        
        $return = [
            'courses' => array_values($courses),
            'hasMoreCourses' => ($coursesdata['count'] > ($start+$limit)),
            'total' => $coursesdata['count'],
        ];

        return $return;

    }
    /**
     * Returns description of method result value.
     */
    public static function get_courses_returns()
    {
        return new external_single_structure([
            'total' => new external_value(PARAM_INT, 'total'),
            'hasMoreCourses' => new external_value(PARAM_BOOL, 'hasMoreLessons'),
            'courses' => new external_multiple_structure(
                new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'name' => new external_value(PARAM_RAW, 'name'),
                        'description' => new external_value(PARAM_RAW, 'description'),
                        'category' => new external_value(PARAM_RAW, 'category'),
                        'visible' => new external_value(PARAM_RAW, 'visible'),
                        'resources' => new external_multiple_structure(
                            new external_single_structure(
                                array(
                                    'rname' => new external_value(PARAM_RAW, 'rname'),
                                    'modname' => new external_value(PARAM_RAW, 'modname'),
                                    'instance' => new external_value(PARAM_INT, 'instance'),
                                    'componentid' => new external_value(PARAM_INT, 'componentid'),
                                    'modicon' => new external_value(PARAM_RAW, 'modicon'),
                                    'disabled' => new external_value(PARAM_BOOL, 'disabled'),
                                )
                            ), 'Resources', VALUE_OPTIONAL,
                        ),
                    )
                ),
            ),
        ]);
    }
    
}
