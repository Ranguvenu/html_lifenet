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
 * External file of lessons block
 *
 * @package   block_lessons
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once $CFG->libdir . '/externallib.php';
require_once $CFG->dirroot . '/blocks/lessons/lib.php';
use block_lessons\lessons as lessons;

class block_lessons_external extends external_api
{
    /**
     * Describes the parameters for recommended_courses_view webservice.
     * @return external_function_parameters
     */
    public static function getlessons_parameters()
    {
        return new external_function_parameters([
            'options' => new external_value(PARAM_RAW, 'The paging data for the service'),
            'dataoptions' => new external_value(PARAM_RAW, 'The data for the service'),
            'offset' => new external_value(PARAM_INT, 'Number of items to skip from the begging of the result set',
                VALUE_DEFAULT, 0),
            'limit' => new external_value(PARAM_INT, 'Maximum number of results to return',
                VALUE_DEFAULT, 0),
            'contextid' => new external_value(PARAM_INT, 'contextid'),
            'filterdata' => new external_value(PARAM_RAW, 'The data for the service'),
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
    public static function getlessons(
        $options,
        $dataoptions,
        $offset = 0,
        $limit = 0,
        $contextid,
        $filterdata
    ) {
        global $OUTPUT, $CFG, $DB, $USER, $PAGE;
        $PAGE->set_context($contextid);
        $params = self::validate_parameters(
            self::getlessons_parameters(),
            [
                'options' => $options,
                'dataoptions' => $dataoptions,
                'offset' => $offset,
                'limit' => $limit,
                'contextid' => $contextid,
                'filterdata' => $filterdata,
            ]
        );
        $offset = $params['offset'];
        $limit = $params['limit'];
        $decodedata = json_decode($params['dataoptions']);
        $filtervalues = json_decode($filterdata);

        $stable = new \stdClass();
        $stable->thead = false;
        $stable->start = $offset;
        $stable->length = $limit;
        $listofcourses = \get_lessons($stable, $filtervalues);
        $totalcount = $listofcourses['count'];

        return [
            'totalcount' => $totalcount,
            'records' => $listofcourses,
            'options' => $options,
            'dataoptions' => $dataoptions,
            'filterdata' => $filterdata,
        ];

    }
    /**
     * Returns description of method result value.
     */
    public static function getlessons_returns()
    {
        return new external_single_structure([
            'options' => new external_value(PARAM_RAW, 'The paging data for the service'),
            'dataoptions' => new external_value(PARAM_RAW, 'The data for the service'),
            'totalcount' => new external_value(PARAM_INT, 'total number of skills in result set'),
            'filterdata' => new external_value(PARAM_RAW, 'The data for the service'),
            'records' => new external_single_structure(
                array(

                    'length' => new external_value(PARAM_INT, 'length', VALUE_OPTIONAL),
                    'hascourses' => new external_multiple_structure(
                        new external_single_structure(
                            array(
                                'id' => new external_value(PARAM_INT, 'id'),
                                'name' => new external_value(PARAM_RAW, 'name'),
                                'description' => new external_value(PARAM_RAW, 'description'),
                                'visible' => new external_value(PARAM_INT, 'visible'),
                                'image' => new external_value(PARAM_RAW, 'image'),
                                'category' => new external_value(PARAM_RAW, 'category'),
                            ),
                        )
                    ),
                )
            ),
        ]);
    }

    /**
     * Describes the parameters for recommended_courses_view webservice.
     * @return external_function_parameters
     */
    public static function get_lessons_parameters()
    {
        return new external_function_parameters([
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
    public static function get_lessons(
        $page
    ) {
        global $OUTPUT, $CFG, $DB, $USER, $PAGE;

        $params = self::validate_parameters(
            self::get_lessons_parameters(),
            [
                'page' => $page,
            ]
        );
        $limit = 10;
        $start = $page * $limit;

        $lessons = new lessons();
        $lessonsdata = $lessons->mylessonsinfo('', $start, $limit);

        $return = [
            'lessons' => $lessonsdata['lessons'],
            'hasMoreLessons' => ($lessonsdata['total'] > ($start+$limit)),
            'total' => $lessonsdata['total'],
        ];

        return $return;

    }
    /**
     * Returns description of method result value.
     */
    public static function get_lessons_returns()
    {
        return new external_single_structure([
            'total' => new external_value(PARAM_INT, 'total'),
            'hasMoreLessons' => new external_value(PARAM_BOOL, 'hasMoreLessons'),
            'lessons' => new external_multiple_structure(
                new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_RAW, 'id'),
                        'fullname' => new external_value(PARAM_RAW, 'name'),
                        'shortname' => new external_value(PARAM_RAW, 'shortname'),
                        'description' => new external_value(PARAM_RAW, 'description'),
                        'courseimage' => new external_value(PARAM_RAW, 'courseimage'),
                        'categoryid' => new external_value(PARAM_RAW, 'categoryid'),
                        'categoryname' => new external_value(PARAM_RAW, 'categoryname'),
                    )
                ),
            ),
        ]);
    }
    
}
