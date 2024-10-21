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
 * Contact page
 *
 * @package    local_notifications
 * @copyright  2023 Moodle India Information Solutions Pvt Ltd
 * @author     Renu Verma (renu.varma@moodle.com).
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_broadcast\forms;

use moodleform;

require_once($CFG->dirroot . '/lib/formslib.php');

class communication_form extends moodleform {

    public function definition() {
        global $DB, $CFG;
        $context = \context_system::instance();

        $mform = $this->_form;
        $id = $this->_customdata['id'];

        $choices = [null => get_string('select')] + get_string_manager()->get_list_of_countries();
        $mform->addElement('select', 'country', get_string('selectacountry'), $choices);
        $mform->addRule('country', null, 'required', null, 'client');
        $mform->setType('country', PARAM_RAW);

        $editoroptions = array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1, 'changeformat' => 1, 'context' => $context, 'noclean' => 1, 'trusttext' => 0);

        $mform->addElement('editor', 'message', get_string('motivationmessage', 'block_broadcast'), null, $editoroptions);
        $mform->addRule('message', null, 'required', null, 'client');
        $mform->setType('message', PARAM_RAW);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setConstant('id', $id);

        $string = ($id > 0) ? get_string('update') : get_string('add');

        $this->add_action_buttons(true, $string);
    }

    /**
     * validate form
     *
     * @param [object] $data
     * @param [object] $files
     * @return costcenter validation errors
     */
    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        if ($data['country'] == 0) {
            $errors['country'] = get_string('required');
        }

        $sql = "SELECT id
                  FROM {block_broadcast_messages}
                 WHERE country = ? ";
        $exists = $DB->get_field_sql($sql, [$data['country']]);
        if ($exists && $data['id'] != $exists) {
            $errors['country'] = get_string('exists', 'block_broadcast');
        }

        return $errors;
    }
}
