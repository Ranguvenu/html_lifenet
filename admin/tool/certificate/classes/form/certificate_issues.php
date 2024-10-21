<?php
// This file is part of the tool_certificate plugin for Moodle - http://moodle.org/
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
 * Issue new certificate for users.
 *
 * @package    tool_certificate
 * @copyright  2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_certificate\form;

use context;
use core_form\dynamic_form;
use moodle_url;
use tool_certificate\template;
use tool_certificate\certificate as certificate_manager;

/**
 * Certificate issues form class.
 *
 * @package    tool_certificate
 * @copyright  2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class certificate_issues extends dynamic_form {

    /** @var template */
    protected $template;

    /**
     * Get template
     *
     * @return template
     */
    protected function get_template(): template {
        if ($this->template === null) {
            $this->template = template::instance($this->_ajaxformdata['tid']);
        }
        return $this->template;
    }

    /**
     * Definition of the form with user selector and expiration time to issue certificates.
     */
    public function definition() {
        global $DB;
        $mform = $this->_form;
        $mform->setDisableShortforms();
        // Add empty header for consistency.
        $mform->addElement('header', 'hdr', '');

        $mform->addElement('hidden', 'tid');
        $mform->setType('tid', PARAM_INT);
        $mform->setDefault('tid', $this->_ajaxformdata['tid']);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
       
        if (!isset($this->_ajaxformdata['id'])) {
            // Users.
            $options = [
                'ajax' => 'tool_certificate/form-potential-user-selector',
                'multiple' => true,
                'data-itemid' => $this->get_template()->get_id(),
            ];
            $selectstr = get_string('selectuserstoissuecertificatefor', 'tool_certificate');
            $mform->addElement('autocomplete', 'users', $selectstr, [], $options);
        } else {
            $record = $DB->get_record('tool_certificate_issues', ['id' => $this->_ajaxformdata['id']]);
            $user = $DB->get_record('user', ['id' => $record->userid]);          
            $username = fullname($user, true);
            $mform->addElement('hidden', 'userid');
            $mform->setType('userid', PARAM_INT);
            $mform->setDefault('userid', $user->id);
            $div = '<div class="form-group row  fitem">
                    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">        
                        <label id="id_open_age_disable_s4pDRD58kebJwLH_label" class="d-inline word-break " for="id_open_age_disable_s4pDRD58kebJwLH">
                        '.get_string('demotmplusername','tool_certificate').'
                        </label>
                        <div class="form-label-addon d-flex align-items-center align-self-start">                        
                        </div>
                    </div>
                    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                    '.$username.'
                    </div>
                </div>';
                $mform->addElement('html', $div);
        }        

        // Expiry date.
        certificate_manager::add_expirydate_to_form($mform);
    }

    /**
     * Returns context where this form is used
     * @return context
     */
    protected function get_context_for_dynamic_submission(): context {       
        return $this->get_template()->get_context();
    }

    /**
     * Check if current user has access to this form, otherwise throw exception
     *
     * Sometimes permission check may depend on the action and/or id of the entity.
     * If necessary, form data is available in $this->_ajaxformdata or
     * by calling $this->optional_param()
     */
    protected function check_access_for_dynamic_submission(): void {
       
    }

    /**
     * Process the form submission, used if form was submitted via AJAX
     *
     * This method can return scalar values or arrays that can be json-encoded, they will be passed to the caller JS.
     *
     * @return int number of issues created
     */
    public function process_dynamic_submission(): int {
        global $DB;        
        $data = $this->get_data();       
        $i = 0;
        $expirydate = certificate_manager::calculate_expirydate($data->expirydatetype, $data->expirydateabsolute,
            $data->expirydaterelative);
        if ($this->_ajaxformdata['id']) {
            $issuerecord = $DB->get_record('tool_certificate_issues', ['id' => $this->_ajaxformdata['id']]);
           
            $result = $this->get_template()->issue_certificate($data->userid, $expirydate, [],'tool_certificate', $issuerecord->courseid);
                    if ($result) {
                        $i++;
                    }
        } else {
            $ajax = (object)$this->_ajaxformdata;   
            foreach ($ajax->users as $userid) {
                if ($this->get_template()->can_issue($userid)) {
                    $result = $this->get_template()->issue_certificate($userid, $expirydate);
                    if ($result) {
                        $i++;
                    }
                }
            }
        }
        return $i;
    }

    /**
     * Load in existing data as form defaults
     *
     * Can be overridden to retrieve existing values from db by entity id and also
     * to preprocess editor and filemanager elements
     */
    public function set_data_for_dynamic_submission(): void {
        global $DB;
        $data = [];        
        if ($this->_ajaxformdata['id']) {          
            $issuerecord = $DB->get_record('tool_certificate_issues', ['id' => $this->_ajaxformdata['id']]);           
            $data = $issuerecord;
            $data->tid = $this->_ajaxformdata['tid'];
            $data->id = $this->_ajaxformdata['id'];
            $data->expirydatetype = ($issuerecord->expires == 0)? certificate_manager::DATE_EXPIRATION_NEVER : certificate_manager::DATE_EXPIRATION_ABSOLUTE; 
            $data->expirydateabsolute = $issuerecord->expires;
                      
        }
        //$this->set_data($this->_ajaxformdata);
        $this->set_data($data);

    }

    /**
     * Returns url to set in $PAGE->set_url() when form is being rendered or submitted via AJAX
     *
     * This is used in the form elements sensitive to the page url, such as Atto autosave in 'editor'
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        return new moodle_url('/admin/tool/certificate/certificates.php', [
            'templateid' => $this->_ajaxformdata['tid'],
            'id' => $this->_ajaxformdata['id'],
        ]);
    }
}
