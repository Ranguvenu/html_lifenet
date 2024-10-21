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
 * Payment subsystem callback implementation for enrol_fee.
 *
 * @package    local_certificate
 * @category   payment
 * @copyright  2024 Shamala Kandula <shamala.kandula@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_certificate\payment;

use tool_certificate\template;
require_once($CFG->dirroot.'/course/lib.php');

/**
 * Payment subsystem callback implementation for enrol_fee.
 *
 * @copyright  2024 Shamala Kandula <shamala.kandula@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class service_provider implements \core_payment\local\callback\service_provider {

    /**
     * Callback function that returns the enrolment cost and the accountid
     * for the course that $instanceid enrolment instance belongs to.
     *
     * @param string $paymentarea Payment area
     * @param int $instanceid 
     * @return \core_payment\local\entities\payable
     */
    public static function get_payable(string $paymentarea, int $instanceid): \core_payment\local\entities\payable {
        global $DB;
        $userissue = $DB->get_record('tool_certificate_issues',['id' => $instanceid]);
        $templatedata = $DB->get_record('tool_certificate_templates',['id' => $userissue->templateid]);
        $account = $DB->get_record('payment_accounts',['contextid' => 1]);
        return new \core_payment\local\entities\payable($templatedata->cost, $templatedata->currency, $account->id);
    }

    /**
     * Callback function that returns the URL of the page the user should be redirected to in the case of a successful payment.
     *
     * @param string $paymentarea Payment area
     * @param int $instanceid The enrolment instance id
     * @return \moodle_url
     */
    public static function get_success_url(string $paymentarea, int $instanceid): \moodle_url {
        
        return new \moodle_url('/admin/tool/certificate/my.php');
    }

    /**
     * Callback function that delivers what the user paid for to them.
     *
     * @param string $paymentarea
     * @param int $instanceid The enrolment instance id
     * @param int $paymentid payment id as inserted into the 'payments' table, if needed for reference
     * @param int $userid The userid the order is going to deliver to
     * @return bool Whether successful or not
     */
    public static function deliver_order(string $paymentarea, int $instanceid, int $paymentid, int $userid): bool {
        global $DB;
        $userissue = $DB->get_record('tool_certificate_issues',['id' => $instanceid]);
        $course = $DB->get_record('course',['id' => $userissue->courseid]);  
        $contextid = get_category_or_system_context($course->category)->id;
        $templaterecord = \tool_certificate\persistent\template::get_record(['contextid' => $contextid]);
        $template = template::instance($templaterecord->get('id'));
        
        $expirydate = strtotime(date('Y-m-d', strtotime('+1 year')));
        $issueid = $template->issue_certificate($userissue->userid, $expirydate, [], 'tool_certificate', $userissue->courseid);
        $obj = new \stdClass();
        $obj->userid = $userid;
        $obj->courseid = $userissue->courseid;
        $obj->cost = $templaterecord->get('cost').' '.$templaterecord->get('currency');
        template::send_renewalpayment_notification($obj);
        return true;
    }
}
