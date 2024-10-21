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
 * Strings for component 'auth_google', language 'en'
 *
 * @package   auth_adwebservice
 * @author Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'OTP';
$string['auth_otpserviceip'] = 'OTP Service URL';
$string['auth_ipusername'] = 'OTP username';
$string['auth_apikey'] = 'OTP API key';
$string['auth_templateid'] = 'OTP API templatename';
$string['auth_broadcastid'] = 'OTP API broadcastname';
$string['auth_websiteparam'] = 'OTP API websiteparamname';

$string['auth_otpservicereportip'] = 'OTP Delivery Report Service URL';
$string['auth_otpserversettings'] ='OTP Settings ';
$string['auth_otpservicedescription'] = 'TOTP Web Service Server Settings are given here. ';
$string['moreproviderlink'] = 'Sign-in with another service.';
$string['signinwithanaccount'] = 'Log in with:';
$string['noaccountyet'] = 'You do not have permission to use the site yet. Please contact your administrator and ask them to activate your account.';
$string['applicationid']='Mobile Number:';
$string['otp']='OTP:';
$string['generateotp']='Generate OTP';
$string['notvalidapplicant']='User with phonenumber "{$a->username}" tried to login. This is not valid applicantionID';
$string['astnotvalidapplicant']='User with phonenumber "{$a->username}" tried to login. This is not agent from AsT-EXT';
$string['notvalidphone']='User with phonenumber "{$a->phonenumber}" tried to login. Mobile Number is not valid "{$a->phonenumber}"';
$string['errorcodefromservice']='User with phonenumber "{$a->phonenumber}" tried to login. Error in OTP server"';
$string['hashexistinuser']='User with UserName "{$a->username}" tried to login with "#" in Password';
$string['otpsendtomobile']='OTP "{$a->otp}" send to User phonenumber "{$a->phonenumber}" and Mobile Number "{$a->phonenumber}". User is Valid ready to generate OTP';

$string['otpabovethree']='User with phonenumber "{$a->username}" tried OTP "{$a->otp}" more then {$a->nooftimes} times.';
$string['incorrectotp']='User with phonenumber "{$a->username}" tried incorrect OTP "{$a->otp}" .';
$string['validotpentered']='User with phonenumber "{$a->username}" successfully entered valid OTP "{$a->otp}" .';
$string['otpnotvalid']='User with phonenumber "{$a->username}" is trying invalid OTP "{$a->otp}".';

$string['spaceexistinuser']='User with UserName "{$a->username}" tried to login with space " " in Password';
$string['notvalidapplicant']='User with phonenumber "{$a->username}" tried to login. This is not approved applicantionID';
$string['nototpapplicant']='User with phonenumber "{$a->username}" tried to login. This is not OTP applicantionID';

$string['pleaseenterreferalcode']='Please enter referral code';

$string['referalcodeheader']='Enter Referal Code Here';

$string['referalcode'] = 'Referal Code';
$string['referalcode_info'] = 'Referal Code';
$string['referalcode_info_help'] = 'If you have a referral code, please enter it and click the "Submit" button, otherwise, click the "Skip" button.';
$string['apply'] = 'Apply';
$string['skip'] = 'Skip';

$string['otprevoked'] = 'Previously generated password has been revoked due to exceeding the login failure threshold.';
$string['otpperiodwarning'] = 'Minimum period after which another password can be generated not preserved. Try again later.';
$string['revokethreshold'] = 'Revoke threshold';
$string['revokethreshold_help'] = 'Login failures limit causing revoke of the generated password (0 - unlimited).';
$string['minrequestperiod'] = 'Minimum period';
$string['minrequestperiod_help'] = 'A time in seconds after which another password can be generated (0 - unrestricted). Enabled logstore required.';
$string['logstorerequired'] = '<b>Notice: no working logstore! <a href="{$a}">Enable logstore</a> or set time to 0.</b>';
$string['otpconfiguration'] = 'OTP Configuration';
$string['auth_appusername'] = 'App Username';
$string['alreadyregistered'] = 'Mobile Number Already Registered';
