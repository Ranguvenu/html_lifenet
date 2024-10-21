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

namespace local_users\forms;

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/completionlib.php');

use moodleform;
use core_user;
use local_users\functions\lib;

class verification_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        $title = get_string('registrationtitle', 'local_users');
        $mform->addElement(
            'html',
            '<div class="card-title">
            <h3 class="signup-title text-center p-3">' . $title . '</h3>
            </div>'
        );

        $duration = get_config('local_users', 'duration');

        $mform->addElement('hidden', 'duration');
        $mform->setType('duration', PARAM_RAW);
        $mform->setConstant('duration', $duration);

        $mform->addElement('html', '<div class="verificationform text-center">');

            $choices = get_string_manager()->get_list_of_countries();
            $phonecodes = lib::get_phonecodes();
            $country = [];
            foreach ($choices as $key => $value) {
                if ($phonecodes[$key]) {
                    $country[$key] = $key . ' (+' . $phonecodes[$key] . ')';
                }
            }

            $country = [0 => get_string('selectacountry')] + $country;

            $mform->addElement('select', 'country', get_string('contactno', 'local_users'), $country);
            $mform->addRule('country', get_string('required'), 'required', null, 'client');
            $mform->setType('country', PARAM_RAW);
        
            $mform->addElement('text', 'phone1', '');
            $mform->addRule('phone1', get_string('error:numeric', 'local_users'), 'numeric', null, 'client');
            $mform->setType('phone1', PARAM_RAW);
        $mform->addElement('html', '</div>');

        $mform->addElement('html','<div class="signup_form">');
            $mform->addElement('button', 'sentotplink', get_string('validatenumber', 'local_users'));
        $mform->addElement('html','</div>');

        $mform->addElement('html', '<div id="loginpassdiv" style="display:none;">');
            $mform->addElement('password', 'otpcode', get_string('enterotp', 'local_users'), 'maxlength="6"');
            $mform->addElement(
                'html',
                '<div class="forgetpass">
                    <p>
                        <a href="#" class="fp_text" id="resendotplink" style="display:none;">
                            '.get_string('resentotp', 'local_users').'
                        </a>
                    </p>
                </div>'
            );
            $mform->addElement('button', 'validateotp', get_string('confirm', 'local_users'));
        $mform->addElement('html', '</div>');

        $mform->disable_form_change_checker();
    }

    // Custom validation should be added here.
    public function validation($data, $files) {
        $errors = [];
        global $DB, $CFG;
        return $errors;
    }
}
