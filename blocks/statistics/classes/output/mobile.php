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
 * Mobile output class for announcement
 *
 * @package     block_statistics
 * @copyright   2022 Daniel Thies <dethies@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_statistics\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/blocklib.php');
require_once("$CFG->libdir/completionlib.php");

use stdClass;
use course_completion;
use local_resources\lib;
/**
 * Mobile output class for Deft response block
 *
 * @package     block_statistics
 * @copyright   2022 Daniel Thies <dethies@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {
    /**
     * Returns the video time course view for the mobile app.
     * @param array $args Arguments from tool_mobile_get_content WS
     *
     * @return array       HTML, javascript and otherdata
     * @throws \required_capability_exception
     * @throws \coding_exception
     * @throws \require_login_exception
     * @throws \moodle_exception
     */
    public static function view_statistics($args) {
        global $CFG, $DB, $OUTPUT, $USER;

        $data = new \stdClass();

        // $params = [];
        // $params['userid'] = $USER->id;
        // $params['country'] = $USER->country;
        // $params['student'] = 'student';
        // $countsql = "SELECT count(DISTINCT(c.id))";
        // $selectsql = "SELECT DISTINCT(c.id)";
        // $fromsql = " FROM {user} u
        //              JOIN {role_assignments} ra ON ra.userid = u.id
        //              JOIN {role} r ON r.id = ra.roleid
        //              JOIN {context} ctx ON ctx.id = ra.contextid
        //              JOIN {course} c ON c.id = ctx.instanceid
        //              JOIN {course_categories} cc ON c.category = cc.id
        //             WHERE cc.idnumber = :country AND r.shortname = :student AND u.id = :userid AND c.visible = 1 ";
        // $enrolledcourses = $DB->count_records_sql($countsql.$fromsql, $params);
        // $courses = $DB->get_records_sql($selectsql.$fromsql, $params);
        $coursesdata = lib::mycourses();

        $courses = $coursesdata['hascourses'];
        $enrolledcourses = $coursesdata['count'] ?: 0;
        $completedcourses = 0;
        foreach ($courses as $key => $value) {
            $ccompletion = new \completion_completion(['userid' => $USER->id, 'course' => $value->id]);
            if ($ccompletion->is_complete()) {
                $completedcourses++;
            }
        }

        $inprogresscourses = $enrolledcourses - $completedcourses;

        $data->enrolled = $enrolledcourses;
        $data->inprogress = $inprogresscourses;
        $data->completed = $completedcourses;

        $html = $OUTPUT->render_from_template('block_statistics/mobile/latest/statistics', $data);

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



    /**
     * Add venue js library
     *
     * @param array $args Arguments from tool_mobile_get_content WS
     */
    public static function init($args): array {
        global $CFG;

        $js = "";

        return [
            'javascript' => $js,
        ];
    }
}
