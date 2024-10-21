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

namespace auth_otp\form;

use core;
use moodleform;
use context_system;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); ///  It must be included from a Moodle page
}

require_once "{$CFG->dirroot}/lib/formslib.php";

/**
 * Referalcode modal form
 *
 * @package    auth_otp
 * @copyright  2022 e abyas  <info@eabyas.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class referalcode_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form;

        $mform->addElement('text', 'referalcode', get_string('referalcode', 'auth_otp'), array());
        // $mform->addHelpButton('referalcode','referalcode_info','auth_otp');
        // $mform->setType('referalcode', PARAM_RAW);
        
        // $mform->addRule('referalcode', get_string('pleaseenterreferalcode', 'auth_otp'), 'required', null);

        $mform->disable_form_change_checker();

    }

    /**
     * Perform some moodle validation.
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {

        global $DB;

        $errors = parent::validation($data, $files);

        if(isset($data['referalcode']) && empty($data['referalcode'])){

            $errors['referalcode']=get_string('pleaseenterreferalcode', 'auth_otp');

        }


        $referralcode = $data['referalcode'];

        if(!empty($referralcode)) {
                $sql = "SELECT id, firstname FROM {user} WHERE concat(firstname,'',id) =:couponcode";
                $params = ['couponcode'=>$referralcode];
                $userdata = $DB->get_record_sql($sql, $params); 
                            
                $sqli = "SELECT id, lastname, noof_userid FROM {local_schoolcontact_details} WHERE couponid = :couponcode";
                $param = ['couponcode'=>$referralcode];
                $schoolcontactdata = $DB->get_record_sql($sqli, $param);
            if($schoolcontactdata->id) {
                return true;
            }else if($userdata->id) {
                return true;
            }  else {
             $errors['referalcode'] = get_string('coupinidexistacnt', 'local_users',$user);
            }
        }
        

        return $errors;
    }

}
