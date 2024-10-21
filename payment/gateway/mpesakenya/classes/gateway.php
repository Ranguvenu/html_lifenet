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
 * Contains class for MTN Africa payment gateway.
 *
 * @package    paygw_mpesakenya
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_mpesakenya;

/**
 * The gateway class for MTN Africa payment gateway.
 *
 * @package    paygw_mpesakenya
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gateway extends \core_payment\gateway {
    /**
     * Country - Currencies supported
     *
     * @return array
     */
    public static function get_country_currencies(): array {
        return [
            'BJ' => 'XOF',
            'CM' => 'XAF',
            'TD' => 'XAF',
            'CG' => 'XAF',
            'CD' => 'CDF',
            'GH' => 'GHS',
            'GN' => 'GNF',
            'CI' => 'XOF',
            'LR' => 'LRD',
            'NE' => 'XOF',
            'RW' => 'RWF',
            'ZA' => 'ZAR',
            'UG' => 'UGX',
            'ZM' => 'ZMW',
            'sandbox' => 'EUR',
        ];
    }

    /**
     * Currencies supported
     *
     * @return array
     */
    public static function get_supported_currencies(): array {
        return ['CDF', 'EUR', 'GHS', 'GNF', 'LRD', 'RWF', 'UGX', 'XAF', 'XOF', 'ZAR', 'ZMW', 'KES'];
    }

    /**
     * Countries supported
     *
     * @return array
     */
    public static function get_countries(): array {
        return ['KE', 'UG'];
    }

    /**
     * Countries supported
     *
     * @return array
     */
    private static function get_supported_countries(): array {
        $countries = self::get_countries();
        $strs = get_strings($countries, 'countries');
        $return = [];
        foreach ($countries as $country) {
            $return[$country] = $strs->$country;
        }
        return $return;
    }

    /**
     * Configuration form for the gateway instance
     *
     * Use $form->get_mform() to access the \MoodleQuickForm instance
     *
     * @param \core_payment\form\account_gateway $form
     */
    public static function add_configuration_to_gateway_form(\core_payment\form\account_gateway $form): void {
        $arr = ['consumerkey', 'consumersecret', 'businessshortcode', 'accountreference', 'passkey', 'environment', 'live', 'sandbox', 'country'];
        $strs = get_strings($arr, 'paygw_mpesakenya');
        $mform = $form->get_mform();

        $mform->addElement('text', 'consumerkey', $strs->consumerkey);
        $mform->setType('consumerkey', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('consumerkey', 'consumerkey', 'paygw_mpesakenya');

        $mform->addElement('passwordunmask', 'consumersecret', $strs->consumersecret);
        $mform->setType('consumersecret', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('consumersecret', 'consumersecret', 'paygw_mpesakenya');

        $mform->addElement('text', 'businessshortcode', $strs->businessshortcode);
        $mform->setType('businessshortcode', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('businessshortcode', 'businessshortcode', 'paygw_mpesakenya');

        $mform->addElement('text', 'accountreference', $strs->accountreference);
        $mform->setType('accountreference', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('accountreference', 'accountreference', 'paygw_mpesakenya');

        $mform->addElement('text', 'passkey', $strs->passkey);
        $mform->setType('passkey', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('passkey', 'passkey', 'paygw_mpesakenya');

        $options = self::get_supported_countries();
        $country = $mform->addElement('select', 'country', $strs->country, $options);
        $mform->addHelpButton('country', 'country', 'paygw_mpesakenya');
        $country->setSelected('KE');

        $options = ['live' => $strs->live, 'sandbox' => $strs->sandbox];
        $mform->addElement('select', 'environment', $strs->environment, $options);
        $mform->addHelpButton('environment', 'environment', 'paygw_mpesakenya');

        $mform->addRule('consumerkey', get_string('required'), 'required');
        $mform->addRule('consumersecret', get_string('required'), 'required');
        $mform->addRule('businessshortcode', get_string('required'), 'required');
        $mform->addRule('accountreference', get_string('required'), 'required');
    }

    /**
     * Validates the gateway configuration form.
     *
     * @param \core_payment\form\account_gateway $form
     * @param \stdClass $data
     * @param array $files
     * @param array $errors form errors (passed by reference)
     */
    public static function validate_gateway_form(
        \core_payment\form\account_gateway $form,
        \stdClass $data,
        array $files,
        array &$errors
    ): void {
        $vals = empty($data->consumerkey) || empty($data->consumersecret)  || empty($data->accountreference);
        if ($data->enabled && $vals) {
            $errors['enabled'] = get_string('gatewaycannotbeenabled', 'payment');
        }
    }
}
