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
 * Language strings
 *
 * @package   local_users
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['contactno'] = 'Mobile Number';
$string['firstname'] = 'First Name';
$string['lastname'] = 'Last Name';
$string['pluginname'] = 'Manage Users';
$string['registrationtitle'] = 'Registration Form';
$string['validatenumber'] = 'Verify mobile number';
$string['unameexists'] = 'Username Already exists';
$string['username'] = 'User Name';

$string['settings:duration'] = 'Secret validity duration';
$string['settings:duration_help'] = 'The duration that generated secrets are valid.';
$string['settings:smsapi:key'] = 'Key';
$string['settings:smsapi:key_help'] = 'API key credential.';
$string['settings:smsapi:secret'] = 'Secret';
$string['settings:smsapi:secret_help'] = 'API secret credential.';
$string['users:registration'] = 'User Verification';
$string['resentotp'] = 'Resend OTP';
$string['error:phone1'] = '- Please select country and enter the mobile number';
$string['error:country'] = '- Please select country';
$string['error:somethingwentwrong'] = '- Something went wrong';
$string['confirm'] = 'Verify OTP';
$string['enterotp'] = 'Enter OTP';
$string['error:code'] = 'Enter the verification code sent to your mobile';
$string['error:numeric'] = 'Only numeric values are allowed';
$string['error:phonemaximum'] = 'Only 10 digits are allowed';
$string['error:phoneminimum'] = 'Only 10 digits are allowed';
$string['error:incorrectotp'] = 'Incorrect OTP';
$string['error:otpexpired'] = 'Entered OTP is expired';
$string['success:successfullysentotp'] = '- Successfully sent OTP to your mobile.';
$string['success:successfullyresentotp'] = '- Successfully resent OTP to your mobile.';
$string['task_otpexpire'] = 'Expire OTP';
$string['backtohome'] = 'Back to home';
$string['registraionsuccess'] = 'Your registration is successfully completed';
$string['confirmedmsg'] = 'Your account has been created <a href="{$a}">click here</a> to login.';
$string['alreadyactivatedmsg'] = 'Your account is active <a href="{$a}">click here</a> to login.';
$string['confirmed'] = 'Confirmed';
$string['createnewaccount'] = 'Create new account';
$string['settings:lockout'] = 'Lockout threshold';
$string['settings:lockout_help'] = 'Number of attempts a user can answer input OTP before they are prevented from verification.';
$string['error:lockedusers'] = 'Your mobile number is blocked, try after 30 minutes';
$string['error:lockoutnotification'] = '- You have {$a} attempts left.';
$string['error:lockedsms'] = 'You have reached the limit to generate new OTP, try after 30 minutes';
