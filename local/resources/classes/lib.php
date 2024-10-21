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
 * Library functions for local_resources plugin. 
 *
 * @package    local_resources
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_resources;

/**
 * Class lib
 *
 * This class provides methods to retrieve child categories and courses based on user's country and filters.
 */
class lib {

    /**
     * Retrieve child categories of the category identified by the user's country.
     * Assumes $USER->country corresponds to 'idnumber' field in 'course_categories' table.
     *
     * @return string Comma-separated list of child category IDs if found, otherwise the parent category ID.
     */
    public static function getcategories() {
        global $USER, $DB;
        $parentcategory = $DB->get_field('course_categories', 'id', ['idnumber' => $USER->country]);
        $sql = "SELECT c.*
                  FROM {course_categories} c
                  JOIN {course_categories} pc ON c.path LIKE CONCAT(pc.path, '/%')
                 WHERE pc.id = :parent_id
                   AND c.id <> pc.id
                 ORDER BY c.path";
        $params = ['parent_id' => $parentcategory];
        $childcategories = $DB->get_records_sql($sql, $params);
        if ($childcategories) {
            $child = [];
            foreach ($childcategories as $category) {
                $child[] = $category->parent;
                $child[] = $category->id;
            }
            return implode(',', $child);
        }

        return $parentcategory;
    } // End of function.

    /**
     * Retrieve courses based on user's country and optional search query.
     *
     * @param [object] $stable   An object containing pagination data (not fully implemented in the snippet).
     * @param [object] $filterdata   An object containing filter criteria (including search query).
     * @return [array]    An array of course records matching the criteria.
     */
    public static function mycourses($stable = false, $filterdata = false) {
        global $USER, $DB;
        $params = [];
        $countsql = "SELECT count(DISTINCT(c.id))";
        $sql = "SELECT DISTINCT(c.id), c.fullname as name, c.summary as description, cc.name as category, c.visible ";
        $formsql = " FROM {course} c
                     JOIN {enrol} e ON c.id = e.courseid
                     JOIN {user_enrolments} ue ON e.id = ue.enrolid
                     JOIN {course_categories} cc ON c.category = cc.id
                     JOIN {user} u ON u.id = ue.userid";
        $wheresql = " WHERE 1=1 ";

        // Add conditions based on user's role and visibility of courses.
        if (!is_siteadmin()) {
            if ($filterdata->resources) {
                $wheresql .= " AND c.visible = 1 ";
            } else {
                $categories = self::getcategories();
                if (empty($categories)) {
                    return null;
                }
                $wheresql .= " AND ue.userid = :userid AND c.visible = 1 AND c.category IN ($categories)";
                $params['userid'] = $USER->id;
            }
        }

        // Handle search query filtering.
        if (isset($filterdata->search_query) && trim($filterdata->search_query) != '') {
            $filteredcourses = array_filter(explode(',', $filterdata->search_query));
            $coursearray = [];
            $i = 0;
            if (!empty($filteredcourses)) {
                foreach ($filteredcourses as $key => $value) {
                    $i++;
                    $coursearray[] = $DB->sql_like('c.fullname', ":queryparam$i", false);
                    $params["queryparam$i"] = "%".trim($filterdata->search_query)."%";
                }
                $imploderequests = implode(' OR ', $coursearray);
                $wheresql .= " AND ($imploderequests)";
            }
        }

        $orderby = " ORDER BY c.id DESC ";

        $count = $DB->count_records_sql($countsql . $formsql . $wheresql, $params);

        // Build and execute the final SQL query.
        $finalsql = $sql . $formsql . $wheresql . $orderby;

        $courses = $DB->get_records_sql($finalsql, $params, $stable->start, $stable->length);

        return [
            'hascourses' => $courses,
            'count' => $count,
            'length' => $count
        ];
    } // End of function.
} // End of class.
