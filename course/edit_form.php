<?php

use core\di;
use core\hook;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir . '/pdflib.php');

/**
 * The form for handling editing a course.
 */
class course_edit_form extends moodleform {
    protected $course;
    protected $context;

    /**
     * Form definition.
     */
    function definition() {
        global $CFG, $PAGE;

        $mform    = $this->_form;
        $PAGE->requires->js_call_amd('core_course/formatchooser', 'init');
        $PAGE->requires->js_call_amd('local_custom_courses/showpopup');
        
        $course        = $this->_customdata['course']; // this contains the data of this form
        $category      = $this->_customdata['category'];
        $editoroptions = $this->_customdata['editoroptions'];
        $returnto = $this->_customdata['returnto'];
        $returnurl = $this->_customdata['returnurl'];

        $systemcontext   = context_system::instance();
        $categorycontext = context_coursecat::instance($category->id);

        if (!empty($course->id)) {
            $coursecontext = context_course::instance($course->id);
            $context = $coursecontext;
        } else {
            $coursecontext = null;
            $context = $categorycontext;
        }

        $courseconfig = get_config('moodlecourse');

        $this->course  = $course;
        $this->context = $context;

        // Form definition with new course defaults.
        $mform->addElement('header','general', get_string('general', 'form'));

        $mform->addElement('hidden', 'returnto', null);
        $mform->setType('returnto', PARAM_ALPHANUM);
        $mform->setConstant('returnto', $returnto);

        $mform->addElement('hidden', 'returnurl', null);
        $mform->setType('returnurl', PARAM_LOCALURL);
        $mform->setConstant('returnurl', $returnurl);

        $mform->addElement('text','fullname', get_string('fullnamecourse'),'maxlength="254" size="50"');
        $mform->addHelpButton('fullname', 'fullnamecourse');
        $mform->addRule('fullname', get_string('missingfullname'), 'required', null, 'client');
        $mform->setType('fullname', PARAM_TEXT);
        if (!empty($course->id) and !has_capability('moodle/course:changefullname', $coursecontext)) {
            $mform->hardFreeze('fullname');
            $mform->setConstant('fullname', $course->fullname);
        }

        $mform->addElement('text', 'shortname', get_string('shortnamecourse'), 'maxlength="100" size="20"');
        $mform->addHelpButton('shortname', 'shortnamecourse');
        $mform->addRule('shortname', get_string('missingshortname'), 'required', null, 'client');
        $mform->setType('shortname', PARAM_TEXT);
        if (!empty($course->id) and !has_capability('moodle/course:changeshortname', $coursecontext)) {
            $mform->hardFreeze('shortname');
            $mform->setConstant('shortname', $course->shortname);
        }

        // Verify permissions to change course category or keep current.
        if (empty($course->id)) {
            if (has_capability('moodle/course:create', $categorycontext)) {
                $displaylist = core_course_category::make_categories_list('moodle/course:create');
                $mform->addElement('autocomplete', 'category', get_string('coursecategory'), $displaylist);
                $mform->addRule('category', null, 'required', null, 'client');
                $mform->addHelpButton('category', 'coursecategory');
                $mform->setDefault('category', $category->id);
            } else {
                $mform->addElement('hidden', 'category', null);
                $mform->setType('category', PARAM_INT);
                $mform->setConstant('category', $category->id);
            }
        } else {
            if (has_capability('moodle/course:changecategory', $coursecontext)) {
                $displaylist = core_course_category::make_categories_list('moodle/course:changecategory');
                if (!isset($displaylist[$course->category])) {
                    //always keep current
                    $displaylist[$course->category] = core_course_category::get($course->category, MUST_EXIST, true)
                        ->get_formatted_name();
                }
                $mform->addElement('autocomplete', 'category', get_string('coursecategory'), $displaylist,
                ['onchange' => '(function(e){require("local_custom_courses/showpopup").certificate_dropdown() })(event)']);
                $mform->addRule('category', null, 'required', null, 'client');
                $mform->addHelpButton('category', 'coursecategory');
            } else {
                //keep current
                $mform->addElement('hidden', 'category', null);
                $mform->setType('category', PARAM_INT);
                $mform->setConstant('category', $course->category);
            }
        }

        $choices = array();
        $choices['0'] = get_string('hide');
        $choices['1'] = get_string('show');
        $mform->addElement('select', 'visible', get_string('coursevisibility'), $choices);
        $mform->addHelpButton('visible', 'coursevisibility');
        $mform->setDefault('visible', $courseconfig->visible);
        if (!empty($course->id)) {
            if (!has_capability('moodle/course:visibility', $coursecontext)) {
                $mform->hardFreeze('visible');
                $mform->setConstant('visible', $course->visible);
            }
        } else {
            if (!guess_if_creator_will_have_course_capability('moodle/course:visibility', $categorycontext)) {
                $mform->hardFreeze('visible');
                $mform->setConstant('visible', $courseconfig->visible);
            }
        }

        // Download course content.
        if ($CFG->downloadcoursecontentallowed) {
            $downloadchoices = [
                DOWNLOAD_COURSE_CONTENT_DISABLED => get_string('no'),
                DOWNLOAD_COURSE_CONTENT_ENABLED => get_string('yes'),
            ];
            $sitedefaultstring = $downloadchoices[$courseconfig->downloadcontentsitedefault];
            $downloadchoices[DOWNLOAD_COURSE_CONTENT_SITE_DEFAULT] = get_string('sitedefaultspecified', '', $sitedefaultstring);
            $downloadselectdefault = $courseconfig->downloadcontent ?? DOWNLOAD_COURSE_CONTENT_SITE_DEFAULT;

            $mform->addElement('select', 'downloadcontent', get_string('enabledownloadcoursecontent', 'course'), $downloadchoices);
            $mform->addHelpButton('downloadcontent', 'downloadcoursecontent', 'course');
            $mform->setDefault('downloadcontent', $downloadselectdefault);

            if ((!empty($course->id) && !has_capability('moodle/course:configuredownloadcontent', $coursecontext)) ||
                    (empty($course->id) &&
                    !guess_if_creator_will_have_course_capability('moodle/course:configuredownloadcontent', $categorycontext))) {
                $mform->hardFreeze('downloadcontent');
                $mform->setConstant('downloadcontent', $downloadselectdefault);
            }
        }

        // Get the task to change automatically the course visibility when the current day matches the course start date.
        $task = \core\task\manager::get_scheduled_task('\core\task\show_started_courses_task');
        $startdatestring = 'startdate';
        if (!empty($task) && !$task->get_disabled()) {
            // When the task is enabled, display a different help message.
            $startdatestring = 'startdatewithtaskenabled';
        }
        $mform->addElement('date_time_selector', 'startdate', get_string('startdate'));
        $mform->addHelpButton('startdate', $startdatestring);
        $date = (new DateTime())->setTimestamp(usergetmidnight(time()));
        $date->modify('+1 day');
        $mform->setDefault('startdate', $date->getTimestamp());

        // Get the task to change automatically the course visibility when the current day matches the course end date.
        $task = \core\task\manager::get_scheduled_task('\core\task\hide_ended_courses_task');
        $enddatestring = 'enddate';
        if (!empty($task) && !$task->get_disabled()) {
            // When the task is enabled, display a different help message.
            $enddatestring = 'enddatewithtaskenabled';
        }
        $mform->addElement('date_time_selector', 'enddate', get_string('enddate'), array('optional' => true));
        $mform->addHelpButton('enddate', $enddatestring);

        if (!empty($CFG->enablecourserelativedates)) {
            $attributes = [
                'aria-describedby' => 'relativedatesmode_warning'
            ];
            if (!empty($course->id)) {
                $attributes['disabled'] = true;
            }
            $relativeoptions = [
                0 => get_string('no'),
                1 => get_string('yes'),
            ];
            $relativedatesmodegroup = [];
            $relativedatesmodegroup[] = $mform->createElement('select', 'relativedatesmode', get_string('relativedatesmode'),
                $relativeoptions, $attributes);
            $relativedatesmodegroup[] = $mform->createElement('html', html_writer::span(get_string('relativedatesmode_warning'),
                '', ['id' => 'relativedatesmode_warning']));
            $mform->addGroup($relativedatesmodegroup, 'relativedatesmodegroup', get_string('relativedatesmode'), null, false);
            $mform->addHelpButton('relativedatesmodegroup', 'relativedatesmode');
        }

        $mform->addElement('text','idnumber', get_string('idnumbercourse'),'maxlength="100"  size="10"');
        $mform->addHelpButton('idnumber', 'idnumbercourse');
        $mform->setType('idnumber', PARAM_RAW);
        if (!empty($course->id) and !has_capability('moodle/course:changeidnumber', $coursecontext)) {
            $mform->hardFreeze('idnumber');
            $mform->setConstants('idnumber', $course->idnumber);
        }
        $core_component = new core_component();
        $certificate_plugin_exist = $core_component::get_plugin_directory('tool', 'certificate');      
        if($certificate_plugin_exist){
            $params = [];    
          
            if (!empty($course->id)) {
                $contextid = get_category_or_system_context($course->category)->id;                
                $params = ['contextid' => $contextid];
            }
            $certificateslist = \tool_certificate\persistent\template :: get_template_records($params);          
         
            if(!empty($certificateslist)){
                $certificateslist = [0 => '']+$certificateslist;
            }
           
            $certificateoptions = array (
                'ajax' => 'local_custom_courses/form-options-selector',
                'class' => 'el_certificate',
                'data-type' => 'certificate_list',
                'readonly' => 'readonly'
            );

            $mform->addElement('autocomplete', 'certificateid', get_string('certificatetemplate', 'tool_certificate'), $certificateslist, $certificateoptions);
            $mform->setType('certificateid', PARAM_RAW);
            $mform->setDefault('certificateid', $course->certificate);

        }

        // Description.
        $mform->addElement('header', 'descriptionhdr', get_string('description'));
        $mform->setExpanded('descriptionhdr');

        $mform->addElement('editor','summary_editor', get_string('coursesummary'), null, $editoroptions);
        $mform->addHelpButton('summary_editor', 'coursesummary');
        $mform->setType('summary_editor', PARAM_RAW);
        $summaryfields = 'summary_editor';

        if ($overviewfilesoptions = course_overviewfiles_options($course)) {
            $mform->addElement('filemanager', 'overviewfiles_filemanager', get_string('courseoverviewfiles'), null, $overviewfilesoptions);
            $mform->addHelpButton('overviewfiles_filemanager', 'courseoverviewfiles');
            $summaryfields .= ',overviewfiles_filemanager';
        }

        if (!empty($course->id) and !has_capability('moodle/course:changesummary', $coursecontext)) {
            // Remove the description header it does not contain anything any more.
            $mform->removeElement('descriptionhdr');
            $mform->hardFreeze($summaryfields);
        }

        // Course format.
        $mform->addElement('header', 'courseformathdr', get_string('type_format', 'plugin'));

        $courseformats = get_sorted_course_formats(true);
        $formcourseformats = new core\output\choicelist();
        $formcourseformats->set_allow_empty(false);
        foreach ($courseformats as $courseformat) {
            $definition = [];
            $component = "format_$courseformat";
            if (get_string_manager()->string_exists('plugin_description', $component)) {
                $definition['description'] = get_string('plugin_description', $component);
            }
            $formcourseformats->add_option(
                $courseformat,
                get_string('pluginname', "format_$courseformat"),
                [
                    'description' => $definition,
                ],
            );
        }
        if (isset($course->format)) {
            $course->format = course_get_format($course)->get_format(); // Replace with default if not found.
            if (!in_array($course->format, $courseformats)) {
                // This format is disabled. Still display it in the dropdown.
                $formcourseformats->add_option(
                    $course->format,
                    get_string('withdisablednote', 'moodle', get_string('pluginname', 'format_'.$course->format)),
                );
            }
        }

        $mform->addElement(
            'choicedropdown',
            'format',
            get_string('format'),
            $formcourseformats,
            ['data-formatchooser-field' => 'selector'],
        );
        $mform->setDefault('format', $courseconfig->format);

        // Button to update format-specific options on format change (will be hidden by JavaScript).
        $mform->registerNoSubmitButton('updatecourseformat');
        $mform->addElement('submit', 'updatecourseformat', get_string('courseformatudpate'), [
            'data-formatchooser-field' => 'updateButton',
            'class' => 'd-none',
        ]);

        // Just a placeholder for the course format options.
        $mform->addElement('hidden', 'addcourseformatoptionshere');
        $mform->setType('addcourseformatoptionshere', PARAM_BOOL);

        // Appearance.
        $mform->addElement('header', 'appearancehdr', get_string('appearance'));

        if (!empty($CFG->allowcoursethemes)) {
            $themeobjects = get_list_of_themes();
            $themes=array();
            $themes[''] = get_string('forceno');
            foreach ($themeobjects as $key=>$theme) {
                if (empty($theme->hidefromselector)) {
                    $themes[$key] = get_string('pluginname', 'theme_'.$theme->name);
                }
            }
            $mform->addElement('select', 'theme', get_string('forcetheme'), $themes);
        }

        if ((empty($course->id) && guess_if_creator_will_have_course_capability('moodle/course:setforcedlanguage', $categorycontext))
                || (!empty($course->id) && has_capability('moodle/course:setforcedlanguage', $coursecontext))) {

            $languages = ['' => get_string('forceno')];
            $languages += get_string_manager()->get_list_of_translations();

            $mform->addElement('select', 'lang', get_string('forcelanguage'), $languages);
            $mform->setDefault('lang', $courseconfig->lang);
        }

        // Multi-Calendar Support - see MDL-18375.
        $calendartypes = \core_calendar\type_factory::get_list_of_calendar_types();
        // We do not want to show this option unless there is more than one calendar type to display.
        if (count($calendartypes) > 1) {
            $calendars = array();
            $calendars[''] = get_string('forceno');
            $calendars += $calendartypes;
            $mform->addElement('select', 'calendartype', get_string('forcecalendartype', 'calendar'), $calendars);
        }

        $options = range(0, 10);
        $mform->addElement('select', 'newsitems', get_string('newsitemsnumber'), $options);
        $courseconfig = get_config('moodlecourse');
        $mform->setDefault('newsitems', $courseconfig->newsitems);
        $mform->addHelpButton('newsitems', 'newsitemsnumber');

        $mform->addElement('selectyesno', 'showgrades', get_string('showgrades'));
        $mform->addHelpButton('showgrades', 'showgrades');
        $mform->setDefault('showgrades', $courseconfig->showgrades);

        $mform->addElement('selectyesno', 'showreports', get_string('showreports'));
        $mform->addHelpButton('showreports', 'showreports');
        $mform->setDefault('showreports', $courseconfig->showreports);

        // Show activity dates.
        $mform->addElement('selectyesno', 'showactivitydates', get_string('showactivitydates'));
        $mform->addHelpButton('showactivitydates', 'showactivitydates');
        $mform->setDefault('showactivitydates', $courseconfig->showactivitydates);

        // Files and uploads.
        $mform->addElement('header', 'filehdr', get_string('filesanduploads'));

        if (!empty($course->legacyfiles) or !empty($CFG->legacyfilesinnewcourses)) {
            if (empty($course->legacyfiles)) {
                //0 or missing means no legacy files ever used in this course - new course or nobody turned on legacy files yet
                $choices = array('0'=>get_string('no'), '2'=>get_string('yes'));
            } else {
                $choices = array('1'=>get_string('no'), '2'=>get_string('yes'));
            }
            $mform->addElement('select', 'legacyfiles', get_string('courselegacyfiles'), $choices);
            $mform->addHelpButton('legacyfiles', 'courselegacyfiles');
            if (!isset($courseconfig->legacyfiles)) {
                // in case this was not initialised properly due to switching of $CFG->legacyfilesinnewcourses
                $courseconfig->legacyfiles = 0;
            }
            $mform->setDefault('legacyfiles', $courseconfig->legacyfiles);
        }

        // Handle non-existing $course->maxbytes on course creation.
        $coursemaxbytes = !isset($course->maxbytes) ? null : $course->maxbytes;

        // Let's prepare the maxbytes popup.
        $choices = get_max_upload_sizes($CFG->maxbytes, 0, 0, $coursemaxbytes);
        $mform->addElement('select', 'maxbytes', get_string('maximumupload'), $choices);
        $mform->addHelpButton('maxbytes', 'maximumupload');
        $mform->setDefault('maxbytes', $courseconfig->maxbytes);

        // PDF font.
        if (!empty($CFG->enablepdfexportfont)) {
            $pdf = new \pdf;
            $fontlist = $pdf->get_export_fontlist();
            // Show the option if the font is defined more than one.
            if (count($fontlist) > 1) {
                $defaultfont = $courseconfig->pdfexportfont ?? 'freesans';
                if (empty($fontlist[$defaultfont])) {
                    $defaultfont = current($fontlist);
                }
                $mform->addElement('select', 'pdfexportfont', get_string('pdfexportfont', 'course'), $fontlist);
                $mform->addHelpButton('pdfexportfont', 'pdfexportfont', 'course');
                $mform->setDefault('pdfexportfont', $defaultfont);
            }
        }

        // Completion tracking.
        if (completion_info::is_enabled_for_site()) {
            $mform->addElement('header', 'completionhdr', get_string('completion', 'completion'));
            $mform->addElement('selectyesno', 'enablecompletion', get_string('enablecompletion', 'completion'));
            $mform->setDefault('enablecompletion', $courseconfig->enablecompletion);
            $mform->addHelpButton('enablecompletion', 'enablecompletion', 'completion');

            $showcompletionconditions = $courseconfig->showcompletionconditions ?? COMPLETION_SHOW_CONDITIONS;
            $mform->addElement('selectyesno', 'showcompletionconditions', get_string('showcompletionconditions', 'completion'));
            $mform->addHelpButton('showcompletionconditions', 'showcompletionconditions', 'completion');
            $mform->setDefault('showcompletionconditions', $showcompletionconditions);
            $mform->hideIf('showcompletionconditions', 'enablecompletion', 'eq', COMPLETION_DISABLED);
        } else {
            $mform->addElement('hidden', 'enablecompletion');
            $mform->setType('enablecompletion', PARAM_INT);
            $mform->setDefault('enablecompletion', 0);
        }

        enrol_course_edit_form($mform, $course, $context);

        $mform->addElement('header','groups', get_string('groupsettingsheader', 'group'));

        $choices = array();
        $choices[NOGROUPS] = get_string('groupsnone', 'group');
        $choices[SEPARATEGROUPS] = get_string('groupsseparate', 'group');
        $choices[VISIBLEGROUPS] = get_string('groupsvisible', 'group');
        $mform->addElement('select', 'groupmode', get_string('groupmode', 'group'), $choices);
        $mform->addHelpButton('groupmode', 'groupmode', 'group');
        $mform->setDefault('groupmode', $courseconfig->groupmode);

        $mform->addElement('selectyesno', 'groupmodeforce', get_string('groupmodeforce', 'group'));
        $mform->addHelpButton('groupmodeforce', 'groupmodeforce', 'group');
        $mform->setDefault('groupmodeforce', $courseconfig->groupmodeforce);

        //default groupings selector
        $options = array();
        $options[0] = get_string('none');
        $mform->addElement('select', 'defaultgroupingid', get_string('defaultgrouping', 'group'), $options);

        if (core_tag_tag::is_enabled('core', 'course') &&
                ((empty($course->id) && guess_if_creator_will_have_course_capability('moodle/course:tag', $categorycontext))
                || (!empty($course->id) && has_capability('moodle/course:tag', $coursecontext)))) {
            $mform->addElement('header', 'tagshdr', get_string('tags', 'tag'));
            $mform->addElement('tags', 'tags', get_string('tags'),
                    array('itemtype' => 'course', 'component' => 'core'));
        }

        // Add custom fields to the form.
        $handler = core_course\customfield\course_handler::create();
        $handler->set_parent_context($categorycontext); // For course handler only.
        $handler->instance_form_definition($mform, empty($course->id) ? 0 : $course->id);

        $hook = new \core_course\hook\after_form_definition($this, $mform);
        di::get(hook\manager::class)->dispatch($hook);

        // When two elements we need a group.
        $buttonarray = array();
        $classarray = array('class' => 'form-submit');
        if ($returnto !== 0) {
            $buttonarray[] = &$mform->createElement('submit', 'saveandreturn', get_string('savechangesandreturn'), $classarray);
        }
        $buttonarray[] = &$mform->createElement('submit', 'saveanddisplay', get_string('savechangesanddisplay'), $classarray);
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        // Communication api call to set the communication data in the form for handling actions for group feature changes.
        // We only need to set the data for courses already created.
        if (!empty($course->id)) {
            $communication = core_communication\helper::load_by_course(
                courseid: $course->id,
                context: $coursecontext,
            );
            $communication->set_data($course);
        }

        // Prepare custom fields data.
        $handler->instance_form_before_set_data($course);
        // Finally set the current form data
        $this->set_data($course);
    }

    /**
     * Fill in the current page data for this course.
     */
    function definition_after_data() {
        global $DB;

        $mform = $this->_form;

        // add available groupings
        $courseid = $mform->getElementValue('id');
        if ($courseid and $mform->elementExists('defaultgroupingid')) {
            $options = array();
            if ($groupings = $DB->get_records('groupings', array('courseid'=>$courseid))) {
                foreach ($groupings as $grouping) {
                    $options[$grouping->id] = format_string($grouping->name);
                }
            }
            core_collator::asort($options);
            $gr_el =& $mform->getElement('defaultgroupingid');
            $gr_el->load($options);
        }

        // add course format options
        $formatvalue = $mform->getElementValue('format');
        if (is_array($formatvalue) && !empty($formatvalue)) {

            $params = array('format' => $formatvalue[0]);
            // Load the course as well if it is available, course formats may need it to work out
            // they preferred course end date.
            if ($courseid) {
                $params['id'] = $courseid;
            }
            $courseformat = course_get_format((object)$params);

            $elements = $courseformat->create_edit_form_elements($mform);
            for ($i = 0; $i < count($elements); $i++) {
                $mform->insertElementBefore($mform->removeElement($elements[$i]->getName(), false),
                        'addcourseformatoptionshere');
            }

            // Remove newsitems element if format does not support news.
            if (!$courseformat->supports_news()) {
                $mform->removeElement('newsitems');
            }
        }

        // Tweak the form with values provided by custom fields in use.
        $handler  = core_course\customfield\course_handler::create();
        $handler->instance_form_definition_after_data($mform, empty($courseid) ? 0 : $courseid);

        $hook = new \core_course\hook\after_form_definition_after_data($this, $mform);
        di::get(hook\manager::class)->dispatch($hook);
    }

    /**
     * Validation.
     *
     * @param array $data
     * @param array $files
     * @return array the errors that were found
     */
    function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        // Add field validation check for duplicate shortname.
        if ($course = $DB->get_record('course', array('shortname' => $data['shortname']), '*', IGNORE_MULTIPLE)) {
            if (empty($data['id']) || $course->id != $data['id']) {
                $errors['shortname'] = get_string('shortnametaken', '', $course->fullname);
            }
        }

        // Add field validation check for duplicate idnumber.
        if (!empty($data['idnumber']) && (empty($data['id']) || $this->course->idnumber != $data['idnumber'])) {
            if ($course = $DB->get_record('course', array('idnumber' => $data['idnumber']), '*', IGNORE_MULTIPLE)) {
                if (empty($data['id']) || $course->id != $data['id']) {
                    $errors['idnumber'] = get_string('courseidnumbertaken', 'error', $course->fullname);
                }
            }
        }

        if ($errorcode = course_validate_dates($data)) {
            $errors['enddate'] = get_string($errorcode, 'error');
        }

        $errors = array_merge($errors, enrol_course_edit_validation($data, $this->context));

        $courseformat = course_get_format((object)array('format' => $data['format']));
        $formaterrors = $courseformat->edit_form_validation($data, $files, $errors);
        if (!empty($formaterrors) && is_array($formaterrors)) {
            $errors = array_merge($errors, $formaterrors);
        }

        // Add the custom fields validation.
        $handler = core_course\customfield\course_handler::create();
        $errors  = array_merge($errors, $handler->instance_form_validation($data, $files));

        $hook = new \core_course\hook\after_form_validation($this, $data, $files);
        di::get(hook\manager::class)->dispatch($hook);
        $pluginerrors = $hook->get_errors();
        if (!empty($pluginerrors)) {
            $errors = array_merge($errors, $pluginerrors);
        }

        return $errors;
    }

    /**
     * Returns course object.
     *
     * @return \stdClass
     */
    public function get_course(): stdClass {
        return $this->course;
    }

    /**
     * Returns context.
     *
     * @return \core\context
     */
    public function get_context(): \core\context {
        return $this->context;
    }
}
