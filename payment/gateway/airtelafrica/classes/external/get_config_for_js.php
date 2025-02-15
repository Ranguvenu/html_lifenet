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
 * This class collects information about a payment with the Airtel Africa payment gateway.
 *
 * @package    paygw_airtelafrica
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types = 1);

namespace paygw_airtelafrica\external;

use core_payment\helper;
use core_external\{external_api, external_function_parameters, external_value, external_single_structure};
use paygw_airtelafrica\airtel_helper;

/**
 * This class collects information about a payment with the Airtel Africa payment gateway.
 *
 * @package    paygw_airtelafrica
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_config_for_js extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'component' => new external_value(PARAM_COMPONENT, 'Component'),
            'paymentarea' => new external_value(PARAM_AREA, 'Payment area in the component'),
            'itemid' => new external_value(PARAM_INT, 'An identifier for payment area in the component'),
        ]);
    }

    /**
     * Returns the config values required by the Airtel Africa JavaScript SDK.
     *
     * @param string $component
     * @param string $paymentarea
     * @param int $itemid
     * @return string[]
     */
    public static function execute(string $component, string $paymentarea, int $itemid): array {
        global $CFG;
        $gateway = 'airtelafrica';
        $arr = ['component' => $component, 'paymentarea' => $paymentarea, 'itemid' => $itemid];
        self::validate_parameters(self::execute_parameters(), $arr);

        $config = helper::get_gateway_configuration($component, $paymentarea, $itemid, $gateway);
        $helper = new airtel_helper($config);
        $user = $helper->current_user_data();
        $payable = helper::get_payable($component, $paymentarea, $itemid);
        $currency = $payable->get_currency();
        return [
            'clientid' => $helper->clientid,
            'brandname' => $config['brandname'],
            'country' => $config['country'],
            'cost' => helper::get_rounded_cost($payable->get_amount(), $currency, helper::get_gateway_surcharge($gateway)),
            'currency' => $currency,
            'phone' => $user['phone'],
            'usercountry' => $user['country'],
            'timeout' => $helper->testing ? 1000 : 20000,
            'reference' => implode(' ', [$component, $paymentarea, $itemid, $user['id']]),
            'url' => $CFG->wwwroot,
        ];
    }

    /**
     * Returns description of method result value.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'clientid' => new external_value(PARAM_TEXT, 'Airtel Africa client ID'),
            'brandname' => new external_value(PARAM_TEXT, 'Brand name'),
            'country' => new external_value(PARAM_TEXT, 'Client country'),
            'cost' => new external_value(PARAM_FLOAT, 'Amount (with surcharge) that will be debited from the payer account.'),
            'currency' => new external_value(PARAM_TEXT, 'ISO4217 Currency code'),
            'phone' => new external_value(PARAM_TEXT, 'User mobile phone'),
            'usercountry' => new external_value(PARAM_TEXT, 'User country'),
            'timeout' => new external_value(PARAM_INT, 'Timout'),
            'reference' => new external_value(PARAM_TEXT, 'Reference'),
            'url' => new external_value(PARAM_TEXT, 'User edit url'),
        ]);
    }
}
