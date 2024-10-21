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

namespace block_lessons;

use stdClass;

/**
 * Manage lessons.
 *
 * @package block_lessons
 * @copyright Arun
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lessons {

    public static function mylessons($search = '', $start = 0, $limit = 5) {
        global $USER, $DB;

        $params = [];
        $countsql = "SELECT count(DISTINCT(c.id))";
        $sql = "SELECT DISTINCT(c.id), c.fullname, c.shortname, c.summary as description, c.category ";
        $formsql = " FROM {user} u
                     JOIN {role_assignments} ra ON ra.userid = u.id
                     JOIN {role} r ON r.id = ra.roleid
                     JOIN {context} ctx ON ctx.id = ra.contextid
                     JOIN {course} c ON c.id = ctx.instanceid
                     JOIN {course_categories} cc ON c.category = cc.id";
        $wheresql = " WHERE 1=1 ";
        if (!is_siteadmin()) {
            $wheresql .= " AND u.id = :userid AND c.visible = 1 ";
            $params['userid'] = $USER->id;
        }

        // For "Global (search box)" filter.
        $queryvar = preg_match("/^[\s\<>~!@#$%^&*()?']+$/u", $search);
        if (!$queryvar) {
            if (isset($search) && trim($search) != '') {
                $filteredcourses = array_filter(explode(',', $search));
                $coursearray = array();
                if (!empty($filteredcourses)) {
                    foreach ($filteredcourses as $key => $value) {
                        $coursearray[] = " c.fullname LIKE '%".trim($value)."%'";
                    }
                    $imploderequests = implode(' OR ', $coursearray);
                    $wheresql .= " AND ($imploderequests)";
                }
            }
        }

        $orderby = " ORDER BY c.id ASC ";

        $total = $DB->count_records_sql($countsql . $formsql . $wheresql, $params);

        $finalsql = $sql . $formsql . $wheresql . $orderby;

        $lessons = $DB->get_records_sql($finalsql, $params, $start, $limit);


        return [
            'lessons' => array_values($lessons),
            'total' => $total
        ];
    }

    public static function mylessonsinfo($search = '', $start = 0, $limit = 5) {
        global $DB, $CFG, $USER, $OUTPUT;

        $mylessonsdata = self::mylessons($search, $start, $limit);

        foreach ($mylessonsdata['lessons'] as $lesson) {
            $coursecontext = \context_course::instance($lesson->id);

            $lesson->categoryid = $lesson->category;
            $lesson->categoryname = $DB->get_field('course_categories', 'name', array('id' => $lesson->category));
            $courseimage = \core_course\external\course_summary_exporter::get_course_image($lesson);
            if (!$courseimage) {
                $courseimage = $OUTPUT->get_generated_url_for_course($coursecontext);
            }
            $lesson->courseimage = $courseimage;
        }

        return $mylessonsdata;
    }


}
