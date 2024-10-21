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

use moodleform;
use core_user;
use local_users\functions\lib;

class registration_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!
        $vid = $this->_customdata['vid'];

        $title = get_string('registrationtitle', 'local_users');
        $mform->addElement(
            'html',
            '<div class="card-title">
            <h3 class="signup-title text-center p-3">' . $title . '</h3>
            </div>'
        );

        $mform->addElement('text', 'firstname', get_string('firstname', 'local_users'));
        $mform->addRule('firstname', get_string('required'), 'required', null, 'client');
        $mform->setType('firstname', PARAM_RAW);

        $mform->addElement('text', 'lastname', get_string('lastname', 'local_users'));
        $mform->addRule('lastname', get_string('required'), 'required', null, 'client');
        $mform->setType('lastname', PARAM_RAW);

        $mform->addElement('text', 'username', get_string('username', 'local_users'));
        $mform->addRule('username', get_string('required'), 'required', null, 'client');
        $mform->setType('username', PARAM_RAW);

        $mform->addElement('passwordunmask', 'password', get_string('password'), 'size="20"');
        $mform->addRule('password', get_string('required'), 'required', null, 'client');
        $mform->setType('password', PARAM_RAW);

        $record = lib::user_registration_record($vid);

        $mform->addElement('hidden', 'phone1');
        $mform->setType('phone1', PARAM_INT);
        $mform->setConstant('phone1', $record->phonenumber);

        $mform->addElement('hidden', 'country');
        $mform->setType('country', PARAM_RAW);
        $mform->setConstant('country', $record->country);

        $email = $record->phonenumber . '@lifenet.com';

        $mform->addElement('hidden', 'email');
        $mform->setType('email', PARAM_RAW);
        $mform->setConstant('email', $email);

        $mform->addElement('hidden', 'vid');
        $mform->setType('vid', PARAM_INT);
        $mform->setConstant('vid', $vid);
    

        $this->add_action_buttons(false, get_string('submit'));

        $mform->disable_form_change_checker();
    }

    // Custom validation should be added here.
    public function validation($data, $files) {
        $errors = [];
        global $DB, $CFG;
        $uname = $data['username'];

        if (strtolower($uname) != $uname) {
            $errors['username'] = get_string('lowercaseunamerequired', 'local_users');
        }

        if ($user = $DB->get_record('user', array('username' => $data['username']), '*', IGNORE_MULTIPLE)) {
            if (empty($data['id']) || $user->id != $data['id']) {
                $errors['username'] = get_string('unameexists', 'local_users');
            }
        }

        $auths = \core_component::get_plugin_list('auth');
        $cannotchangepass = [];
        foreach ($auths as $auth => $unused) {
            $authinst = get_auth_plugin($auth);
            $passwordurl = $authinst->change_password_url();
            if (!($authinst->can_change_password() && empty($passwordurl))) {
                if ($authinst->is_internal()) {
                    // This is unlikely but we can not create account without password.
                    // when plugin uses passwords, we need to set it initially at least.
                } else {
                    $cannotchangepass[] = $auth;
                }
            }
        }

        if (!$data['createpassword']) {
            if (!empty($data['password']) && !in_array($data['auth'], $cannotchangepass)) {

                $errmsg = ''; // Prevent eclipse warning.
                if (!check_password_policy($data['password'], $errmsg)) {
                    $errors['password'] = $errmsg;
                }
            } else if (empty($data['id']) &&
                $data['createpassword'] != 1 && !in_array(
                $data['auth'], $cannotchangepass) && empty(
                $data['password'])) {
                $errors['password'] = get_string('passwordrequired', 'local_users');
            }
        }

        return $errors;
    }
}
