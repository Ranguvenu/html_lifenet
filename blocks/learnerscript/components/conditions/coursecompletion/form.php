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

/** LearnerScript Reports
 * A Moodle block for creating customizable reports
 * @package blocks
 * @author: eAbyas Info Solutions
 * @date: 2017
 */
if (!defined('MOODLE_INTERNAL')) {
    die(get_string('nodirectaccess','block_learnerscript'));    ///  It must be included from a Moodle page
}

require_once($CFG->libdir . '/formslib.php');

class coursecompletion_form extends moodleform {

    var $allowedops = array('=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<=', '<>' => '<>', 'LIKE' => 'LIKE', 'NOT LIKE' => 'NOT LIKE', 'LIKE % %' => 'LIKE % %');

    function definition() {
        global $DB, $USER, $CFG;

        $mform = & $this->_form;

        $mform->addElement('header', 'crformheader', get_string('coursecompletion', 'block_learnerscript'), '');

        $columns = $DB->get_columns('course_completions');

        $coursecolumns = array();
        foreach ($columns as $c) {
            $coursecolumns[$c->name] = $c->name;
        }

        $mform->addElement('select', 'field', get_string('column', 'block_learnerscript'), $coursecolumns);

        $mform->addElement('select', 'operator', get_string('operator', 'block_learnerscript'), $this->allowedops);
        $mform->addElement('text', 'value', get_string('value', 'block_learnerscript'));
        $mform->setType('value', PARAM_RAW);
        // buttons
        $this->add_action_buttons(true, get_string('add'));
    }

    function validation($data, $files) {
        global $DB, $db, $CFG;

        $errors = parent::validation($data, $files);

        if (!in_array($data['operator'], $this->allowedops)) {
            $errors['operator'] = get_string('error_operator', 'block_learnerscript');
        }

        $columns = $DB->get_columns('course_completions');
        $coursecolumns = array();
        foreach ($columns as $c) {
            $coursecolumns[$c->name] = $c->name;
        }

        if (!in_array($data['field'], $coursecolumns)) {
            $errors['field'] = get_string('error_field', 'block_learnerscript');
        }

        if (!is_numeric($data['value']) && preg_match('/^(<|>)[^(<|>)]/i', $data['operator'])) {
            $errors['value'] = get_string('error_value_expected_integer', 'block_learnerscript');
        }

        return $errors;
    }

}
