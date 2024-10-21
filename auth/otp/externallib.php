<?php
require_once("$CFG->libdir/externallib.php");

class auth_otp_external extends external_api {


    public static function auth_otp_generate_parameters() {
        return new external_function_parameters(
        array('application_id' => new external_value(PARAM_RAW, 'Application ID',VALUE_DEFAULT,'') ) );
    }

    public static function auth_otp_generate($application_id) { //Don't forget to set it as static
        global $CFG, $DB,$USER;

        $params = self::validate_parameters(self::auth_otp_generate_parameters(),
            array('application_id' => $application_id));
		require_once($CFG->dirroot . "/auth/otp/lib.php");

        $application_id=str_replace("+", "",$application_id);
        $otpsend=new otp();
		$getresponse=$otpsend->send_otp_touser($application_id);

        return $getresponse;
    }


    public static function auth_otp_generate_returns() {
         return new external_value(PARAM_RAW, 'otpresponse');
    }

    public static function request_otp_parameters() {
        return new external_function_parameters(
            array(
                'username' => new external_value(PARAM_RAW, 'username')
            )
        );
    }

    public static function request_otp($username) {
        global $CFG, $DB, $USER;

        $params = self::validate_parameters(self::request_otp_parameters(),
        array('username' => $username));

        require_once($CFG->dirroot . "/auth/otp/lib.php");

        $username=str_replace("+", "",$username);
        $otp = new otp();
        $status = $otp->send_otp_touser($username);

        return array('status' => $status,'revokethreshold' =>get_config('auth_otp', 'revokethreshold'));
    }

    public static function request_otp_returns() {
        return new external_function_parameters(
            array(
                'status' => new external_value(PARAM_INT, 'status'),
                'revokethreshold' => new external_value(PARAM_INT, 'revokethreshold')
            )
        );
    }

    public static function validate_otp_parameters() {
        return new external_function_parameters(
            array(
                'username' => new external_value(PARAM_RAW, 'username'),
                'password' => new external_value(PARAM_INT, 'password')
            )
        );
    }

    public static function validate_otp($username, $password) {
        global $CFG, $DB, $USER;
        $params = self::validate_parameters(self::validate_otp_parameters(), 
        array('username' => $username, 'password' => $password));

        require_once($CFG->dirroot . "/auth/otp/lib.php");
        $username=str_replace("+", "",$username);

        $otp = new otp();
        $status = $otp->validate_otp($username, $password);

        return array('status' => $status);
    }

    public static function validate_otp_returns() {
        return new external_function_parameters(
            array(
                'status' => new external_value(PARAM_INT, 'status')
            )
        );
    }

    public static function validateuserdetails_parameters() {
        return new external_function_parameters(
            array(
            'username' => new external_value(PARAM_RAW, 'username'),
            'otp' => new external_value(PARAM_RAW, 'otp'),
            'type' => new external_value(PARAM_INT, 'type'),
            'firstname' => new external_value(PARAM_RAW, 'firstname', VALUE_OPTIONAL),
            'lastname' => new external_value(PARAM_RAW, 'lastname', VALUE_OPTIONAL),
            )
        );
    }

    public static function validateuserdetails($username, $otp = 0, $type, $firstname = null, $lastname = null) {
        global $CFG, $DB,$USER;
        $params = self::validate_parameters(self::validateuserdetails_parameters(),
            array('username' => $username, 'otp' => $otp, 'type' => $type, 'firstname' => $firstname, 'lastname' => $lastname));
        require_once($CFG->dirroot . "/auth/otp/lib.php");

        $username=str_replace("+", "",$username);
        $otpsend = new otp();
        if (!empty($username) && $type == 1) {
           $response = $otpsend->validate_application($username,false, $firstname, $lastname);
        } else if (!empty($username) && $type == 2 && !empty($otp)) {
           $response = $otpsend->validate_otp($username, $otp, $firstname, $lastname);
        } else {
            $response = '';
        }
        return $response;
    }

    public static function validateuserdetails_returns() {
        return new external_value(PARAM_RAW, 'response');
    }
    public static function submit_referalcode_parameters() {
           return new external_function_parameters(
            array(
                'contextid' => new external_value(PARAM_INT, 'The context id', false),
                'jsonformdata' => new external_value(PARAM_RAW, 'Submitted Form Data', false),
            )
        );
    }

    public static function submit_referalcode($contextid, $jsonformdata) {
        global $PAGE, $DB, $CFG, $USER;
        $context = context::instance_by_id($contextid, MUST_EXIST);
        self::validate_context($context);
        $serialiseddata = json_decode($jsonformdata);
        $data = array();
        parse_str($serialiseddata, $data);

        $warnings = array();

        // The last param is the ajax submitted data.
        $mform = new auth_otp\form\referalcode_form(null, array(), 'post', '', null, true, $data);
        $validateddata = $mform->get_data();


        if ($validateddata) {
 
            $referalcode = user_referalcode_validation($validateddata->referalcode);
            if($referalcode) {
                set_user_preference('force_dontshow_referalcodeform', 1);
                $return=1;
            }

        } else {
            // Generate a warning.
            throw new moodle_exception('missingauth', 'auth_otp');
        }
        return $return;

    }

    public static function submit_referalcode_returns() {
        return new external_value(PARAM_INT, 'response');
    }

    public static function alter_popup_status_parameters(){
        return new external_function_parameters(
            array(
                'contextid' => new external_value(PARAM_INT, 'The context id', false),
                'status' => new external_value(PARAM_BOOL, 'Status of the popupshown')
            )
        );
    }
    public static function alter_popup_status($contextid, $status){
        global $PAGE;
        $params = self::validate_parameters(
            self::alter_popup_status_parameters(),
            [
                'contextid' => $contextid,
                'status' => $status
            ]
        );
        $value = $status;
        return set_user_preference('force_dontshow_referalcodeform', $value);
    }
    public static function alter_popup_status_returns(){
        return new external_value(PARAM_BOOL, 'return');
    }

}
