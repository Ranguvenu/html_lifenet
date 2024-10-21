<?php
/**
 * This file is part of eAbyas
 *
 * Copyright eAbyas Info Solutons Pvt Ltd, India
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author eabyas  <info@eabyas.in>
 * @package ODL
 * @subpackage blocks_announcement
 */
namespace block_announcement\form;
use core;
use moodleform;
use context_system;
use coursecat;
use html_writer;
require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/lib/badgeslib.php');

class announcement_form extends moodleform {

    /**
     * Defines the form
     */
    public function definition() {
        global $USER, $PAGE, $OUTPUT, $DB;
        $mform = $this->_form;
        $id = $this->_customdata['id'];
        
        $courseid = $this->_customdata['courseid'];
        $context = context_system::instance();
        $mformajax =& $this->_ajaxformdata;

        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);
        
        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);

        $choices = [0 => get_string('select')] + get_string_manager()->get_list_of_countries();
        $mform->addElement('select', 'country', get_string('selectacountry'), $choices);
        $mform->addRule('country', null, 'required', null, 'client');
        $mform->setType('country', PARAM_RAW);

        $mform->addElement('text', 'name', get_string('subject', 'block_announcement'));
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->setType('name', PARAM_RAW);

        $mform->addElement('editor', 'description', get_string('description', 'block_announcement'), null,
        $this->get_description_editor_options());
        
        $mform->addRule('description', null, 'required', null, 'client');
        $mform->addHelpButton('description', 'description','block_announcement');
        $mform->setType('description', PARAM_RAW);

        $mform->addElement(
            'date_selector',
            'startdate',
            get_string('startdate', 'block_announcement'),        
        );

        $mform->addElement(
            'date_selector',
            'enddate',
            get_string('enddate', 'block_announcement'),
        );
       
        $this->add_action_buttons();
    }
    /**
     * Returns the description editor options.
     * @return array
     */
    public function get_description_editor_options() {
        global $CFG;
        
        $context = $this->_customdata['context'];
        if (empty($context)) {
            $context =  context_system::instance();
        }

        $itemid = $this->_customdata['itemid'];

        return [
            'maxfiles'  => EDITOR_UNLIMITED_FILES,
            'maxbytes'  => $CFG->maxbytes,
            'trusttext' => true,
            'context'   => $context,
            'autosave' => false,
            'subdirs'   => file_area_contains_subdirs($context, 'system', 'description', $itemid),
        ];
    }
    
    /**
     * Validates form data
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);

        if (isset($data['country'])) {
            if ($data['country'] == 0) {
                $errors['country'] = get_string('required');
            }
        }

        if (strlen(trim($data['name'])) == 0 && !empty($data['name'])) {
            $errors['name'] = get_string('blankspaces', 'block_announcement');
        }

        if ($data['enddate'] < $data['startdate']) {
            $errors['enddate'] = get_string('nohighandsameenddate', 'block_announcement');
        }

        $sql = "SELECT id
                  FROM {block_announcement}
                 WHERE country = ? ";
        $exists = $DB->get_field_sql($sql, [$data['country']]);
        if ($exists && $data['id'] != $exists) {
            $errors['country'] = get_string('exists', 'block_broadcast');
        }

        return $errors;
    }
}
