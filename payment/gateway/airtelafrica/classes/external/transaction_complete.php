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
 * This class completes a payment with the Airtel Africa payment gateway.
 *
 * @package    paygw_airtelafrica
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

namespace paygw_airtelafrica\external;

use core_payment\helper;
use core_external\{external_api, external_function_parameters, external_value, external_single_structure};
use paygw_airtelafrica\airtel_helper;
/**
 * This class completes a payment with the Airtel Africa payment gateway.
 *
 * @package    paygw_airtelafrica
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class transaction_complete extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'component' => new external_value(PARAM_COMPONENT, 'The component name'),
            'paymentarea' => new external_value(PARAM_AREA, 'Payment area in the component'),
            'itemid' => new external_value(PARAM_INT, 'The item id in the context of the component area'),
            'transactionid' => new external_value(PARAM_TEXT, 'The transaction id coming back from Airtel Africa'),
        ]);
    }

    /**
     * Perform what needs to be done when a transaction is reported to be complete.
     * This function does not take cost as a parameter as we cannot rely on any provided value.
     *
     * @param string $component Name of the component that the itemid belongs to
     * @param string $paymentarea The payment area
     * @param int $itemid An internal identifier that is used by the component
     * @param string $transactionid Airtel Africa order ID
     * @return array
     */
    public static function execute(string $component, string $paymentarea, int $itemid, string $transactionid): array {
        self::validate_parameters(self::execute_parameters(), [
            'component' => $component,
            'paymentarea' => $paymentarea,
            'itemid' => $itemid,
            'transactionid' => $transactionid,
        ]);
        if ($component == 'enrol_fee') {
            $config = helper::get_gateway_configuration($component, $paymentarea, $itemid, 'airtelafrica');
            $helper = new airtel_helper($config);
            $trans = $helper->enrol_user($transactionid, $itemid, $component, $paymentarea);
        } else {
            $config = helper::get_gateway_configuration($component, $paymentarea, $itemid, 'airtelafrica');
            $helper = new airtel_helper($config);
            $trans = $helper->certificate_renewal($transactionid, $itemid, $component, $paymentarea);
        }
        
        return ['success' => (bool)($trans == 'TS'), 'message' => airtel_helper::ta_code($trans)];
    }

    /**
     * Returns description of method result value.
     *
     * @return external_function_parameters
     */
    public static function execute_returns() {
        return new external_function_parameters([
            'success' => new external_value(PARAM_BOOL, 'Whether everything was successful or not.'),
            'message' => new external_value(PARAM_RAW, 'Message (usually the error message).'),
        ]);
    }
}
