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
 * auth_otp external functions and service definitions.
 *
 * @package    auth_otp
 * @category   external
 */

defined('MOODLE_INTERNAL') || die;

$functions = array(

    'auth_otp_request_otp' => array(
        'classname'     => 'auth_otp_external',
        'classpath'     => 'auth/otp/externallib.php',
        'methodname'    => 'request_otp',
        'description'   => 'request_otp',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => false,
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'auth_otp_validate_otp' => array(
        'classname'     => 'auth_otp_external',
        'classpath'     => 'auth/otp/externallib.php',
        'methodname'    => 'validate_otp',
        'description'   => 'validate_otp',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'auth_otp_validateuserdetails' => array(
        'classname'     => 'auth_otp_external',
        'classpath'     => 'auth/otp/externallib.php',
        'methodname'    => 'validateuserdetails',
        'description'   => 'validateuserdetails',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false
    ),
    'auth_otp_submit_referalcode' => array(
        'classname' => 'auth_otp_external',
        'classpath'     => 'auth/otp/externallib.php',
        'methodname' => 'submit_referalcode',
        'description' => 'validate referalcode and update data',
        'ajax' => true,
        'type' => 'write'
    ),
    'auth_otp_alter_popup_status' => array(
        'classname' => 'auth_otp_external',
        'classpath'     => 'auth/otp/externallib.php',
        'methodname' => 'alter_popup_status',
        'description' => 'alter popup status',
        'ajax' => true,
        'type' => 'write'
    ),

);
