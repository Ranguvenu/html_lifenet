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
 * @package     local_prisemforce
 * @copyright   2024 Moodle India Information Solutions Pvt Ltd
 * @author      2024 Shamala <shamala.kandula@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Event observer for local_custom_courses
 */
defined('MOODLE_INTERNAL') || die();
use tool_certificate\template;
use cache;
use local_custom_courses\persistent\certificate_data;
class local_custom_courses_observer {
    protected $template;
    /**
     * Triggered via attemp event.
     *
     * @param \core\event\course_completed $event The triggered event.
     */
    public static function coursecompleted(\core\event\course_completed $event) {
        global $DB, $USER;
        $course = $event->get_record_snapshot('course', $event->courseid);
        $user = $DB->get_record('user',['id' => $event->relateduserid]);
        $certificate = certificate_data::get_record(['courseid' => $course->id]);
        $record = new \stdClass();
        if ($certificate) {
            if ($certificate->get('templateid')) {
                $template = template::instance($certificate->get('templateid'));
                $sqlquery = "SELECT * FROM {tool_certificate_issues} WHERE userid = :userid AND templateid=:templateid AND courseid=:courseid";
                $checkcourse = $DB->get_record_sql($sqlquery, ['userid' => $event->relateduserid, 'templateid' => $certificate->get('templateid') , 'courseid' => $course->id]);
                if (!$checkcourse) {
                    $expirydate = strtotime(date('Y-m-d', strtotime('+1 year')));
                    $issueid = $template->issue_certificate($user->id, $expirydate, [], 'tool_certificate', $event->courseid);
                    if ($issueid) {
                        $sql = "SELECT ci.id, ci.templateid, ct.name, ci.code FROM {tool_certificate_issues} ci 
                        JOIN {tool_certificate_templates} ct ON ct.id = ci.templateid  WHERE ci.id =:issueid";
                        $certificaterecord = $DB->get_record_sql($sql, ['issueid' => $issueid]);
                        $record->certificatename = $certificaterecord->name;
                        $record->certificatecode = $certificaterecord->code;
                    }
                }
            }
        }

        $record->userid = $USER->id;
        $record->courseid = $event->courseid;
        $record->timecompleted = time();
       
        // Initialize the cache
        $cache = cache::make('local_custom_courses', 'mycache');
        $cache->set('coursecompleted', $record);
    }
}
