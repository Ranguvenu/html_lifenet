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
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_custom_courses
 * @copyright   2024 Moodle India Information Solutions Pvt Ltd
 * @author      2024 Shamala <shamala.kandula@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_external\external_api;
use core_external\external_value;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_multiple_structure;
require_once($CFG->dirroot.'/course/lib.php');

/**
 * External functions for local_users.
 *
 * @package   local_custom_courses
 * @copyright 2023 Moodle India Information Solutions Pvt Ltd
 * @author    2023 Shamala <shamala.kandula@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_custom_courses_external extends external_api {
    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters.
     */
    public static function form_option_selector_parameters() {

        $query = new external_value(PARAM_RAW, 'search query');
        $type = new external_value(PARAM_ALPHANUMEXT, 'Type of data', VALUE_REQUIRED);
        $conditions = new external_value(PARAM_RAW, 'Region', VALUE_OPTIONAL);

        $params = array(
            'query' => $query,
            'type' => $type,
            'conditions' => $conditions
        );
        return new external_function_parameters($params);
    }
    /**
     * Gets the list of option details
     *
     * @param string $query 
     * @param string $type
     * @param string $conditions
     * @return array 
     */
    public static function form_option_selector($query, $type, $conditions = []) {
        global $DB;
        $params = array(
            'query' => $query,
            'type' => $type,
            'conditions' => $conditions,
        );

        $conditions = json_decode($params['conditions']);       
        $params = self::validate_parameters(self::form_option_selector_parameters(), $params);
        $options = [];

        switch ($params['type']) {            
            case 'certificate_list':
                $contextid = get_category_or_system_context($conditions)->id;
                $templatelist = $DB->get_records('tool_certificate_templates', ['contextid' => $contextid]);
                if ($templatelist) {
                    $options = $templatelist;
                }
            break;
        }
        $data = $options;
        
        return ['status' => true, 'data' => array_values(json_decode(json_encode(($data)), true))];
    }

    public static function form_option_selector_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_INT, 'status: true if success'),
                'data' => new external_multiple_structure(new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'name' => new external_value(PARAM_RAW, 'name', VALUE_OPTIONAL)
                    )
                ), '', VALUE_OPTIONAL)
            )
        );
    }


}