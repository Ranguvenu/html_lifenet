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
 * Strings for component 'paygw_mpesakenya', language 'en'
 *
 * @package    paygw_mpesakenya
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['amountmismatch'] = 'The amount you attempted to pay does not match the required fee. Your account has not been debited.';
$string['apikey'] = 'API key';
$string['apikey_help'] = 'API key, found on the local money portal.';
$string['authorising'] = 'Authorising the payment. Please wait...';
$string['brandname'] = 'Brand name';
$string['brandname_help'] = 'An optional label that overrides the business name for the your account on the MTN Africa site.';
$string['cannotfetchorderdatails'] = 'Could not fetch payment details from MPesa kenya. Your account has not been debited.';
$string['cleanuptask'] = 'Clean up not completed payment task.';
$string['clientid'] = 'API User';
$string['clientid_help'] = 'The API User ID that MPesa kenya generated for your application.';
$string['country'] = 'Country';
$string['country_help'] = 'In which country is this client located';
$string['environment'] = 'Environment';
$string['environment_help'] = 'You can set this to Sandbox if you are using sandbox accounts (for testing purpose only).
But the use of the MTN sandbox environment is not recommended: MPesa kenya sends telephone nunbers, transaction ids, ... in the callback function using the unsave HTTP Port.
It\'s better to disable the sandbox environement when it is not used.';
$string['failed'] = 'MPesa kenya payment failed';
$string['gatewaydescription'] = 'MPesa kenya is an authorised payment gateway provider for processing mobile money.';
$string['gatewayname'] = 'MPesa kenya';
$string['internalerror'] = 'An internal error has occurred. Please contact us.';
$string['live'] = 'Live';
$string['mtnstart'] = 'We sent you a request for payment.</br>
Please complete the payment using your cell phone.</br>
You have 3 minutes to complete this transaction.</br>
The moment we receive a confirmation by MPesa kenya, you will be able to access the course.';
$string['paymentnotcleared'] = 'payment not cleared by MPesa kenya.';
$string['pluginname'] = 'MPesa Kenya';
$string['pluginname_desc'] = 'The MPesa kenya plugin allows you to receive payments via MPesa kenya.';
$string['privacy:metadata:paygw_mpesakenya'] = 'The MPesa kenya payment gateway stores payment information.';
$string['privacy:metadata:paygw_mpesakenya:moneyid'] = 'The MTN Money id of the payment.';
$string['privacy:metadata:paygw_mpesakenya:paymentid'] = 'The payment id of the payment.';
$string['privacy:metadata:paygw_mpesakenya:timecompleted'] = 'The time the payment was completed.';
$string['privacy:metadata:paygw_mpesakenya:timecreated'] = 'The time the payment was created.';
$string['privacy:metadata:paygw_mpesakenya:transactionid'] = 'The transactionid of the payment.';
$string['privacy:metadata:paygw_mpesakenya:userid'] = 'The userid of the user.';
$string['repeatedorder'] = 'This order has already been processed earlier.';
$string['request_log'] = 'MTN Gateway log';
$string['sandbox'] = 'Sandbox';
$string['start'] = 'Click on the MTN image to start your payment.';
$string['thanks'] = 'THX for your payment.';
$string['unable'] = 'Unable to connect to Mpesa';
$string['validcontinue'] = 'Please wait until we receive confirmation by Aitel, +-30 seconds before you continue.';
$string['validtransaction'] = 'We got a valid transactionid: {$a}';
$string['warning_phone'] = 'Please be sure that this is <strong>your</strong> Mobile phone number and country. You can change the number and country on your <a href="../user/edit.php" title="profile">profile page</a>.</br>
MPesa kenya needs a number <b>with</b> the country code.
(Sample: 46733123451)';
$string['warning_phone1'] = 'Please be sure that this is <strong>your</strong> Mobile phone number and country. You can change the number and country on your';
$string['warning_phone2'] = 'MPesa kenya needs a number <b>with</b> the country code.
(Sample: 46733123451)';


$string['consumerkey'] = 'Consumar key';
$string['consumerkey_help'] = 'Consumar key for MPesa Kenya (Found on https://developer.safaricom.co.ke).';
$string['consumersecret'] = 'Consumar Secret';
$string['consumersecret_help'] = 'Consumar Secret for MPesa Kenya (Found on https://developer.safaricom.co.ke/).';

$string['businessshortcode'] = 'Business Short Code';
$string['businessshortcode_help'] = 'Business Short Code (Found on https://developer.safaricom.co.ke).';
$string['accountreference'] = 'Account Reference';
$string['accountreference_help'] = 'Account Reference for MPesa Kenya (Found on https://developer.safaricom.co.ke/).';

$string['passkey'] = 'Pass Key';
$string['passkey_help'] = 'Pass Key for MPesa Kenya (Found on https://developer.safaricom.co.ke/).';

$string['phoneconfirm'] = 'Please confirm your MPesa Mobile number?';
