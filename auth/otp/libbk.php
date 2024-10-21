<?php


 /**
 * OTP authentication plugin version specification.
 *
 * @package    auth
 * @subpackage otp
 * @copyright  2022 Sreenivas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir.'/filelib.php');

use curl;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
function auth_otp_output_fragment_referalcode_form($args) {
    global $CFG, $DB;

    $args = (object) $args;
    $context = $args->context;
    $o = '';
    $formdata = [];

    if (!empty($args->jsonformdata)) {

        $serialiseddata = json_decode($args->jsonformdata);
        if(is_object($serialiseddata)){
            $serialiseddata = serialize($serialiseddata);
        }
        parse_str($serialiseddata, $formdata);
    }
    $mform = new auth_otp\form\referalcode_form(null, array(), 'post', '', null, true, $formdata);

    $mform->set_data($data);

    if (!empty($formdata)) {
        // If we were passed non-empty form data we want the mform to call validation functions and show errors.
        $mform->is_validated();
    }

    ob_start();
    $mform->display();
    $o .= ob_get_contents();
    ob_end_clean();
    return $o;
}
class otp {

  public function __construct() {
    $this->apiurl = get_config('auth_otp', 'otpserviceip');
    $this->token = get_config('auth_otp', 'apikey');
    $this->websiteparam = get_config('auth_otp', 'websiteparam');
    $this->templateid = get_config('auth_otp', 'templateid');
  }

  /*
   * @method local_logs Get logs
   * @param $event
   * @param $module
   * @param $moduleid
   * @param $description
   * @param $type
   * @output data will be insert into mdl_local_logs table
   */
  function local_logs($event, $module, $moduleid, $description, $type=NULL){
      global $DB, $USER, $CFG;

      $userid                 = $USER->id;
      $log_data               = new stdClass();
      $log_data->event        = $event;
      $log_data->module       = $module;
	    $log_data->moduleid     = $moduleid;
      $log_data->description  = $description;
      $log_data->type         = $type;
      $log_data->timecreated  = time();
      $log_data->timemodified = time();
      $log_data->usercreated  = $userid;
      $log_data->usermodified = $userid;
      $result = $DB->insert_record('local_logs', $log_data);
  }

  public function validate_application($username) {
    global $DB,$CFG;
    $sql = "SELECT u.* FROM {user} u WHERE u.username = ? AND u.confirmed = 1 AND u.auth = 'otp'";
    $validusers = $DB->get_record_sql($sql, [$username]);
    $phonenumber = preg_replace( '/[^0-9]/', '', $validusers->phone1 );
    $phonelength = strlen($phonenumber);
    $appdetails = new stdClass();
	  if (empty($validusers)) {
        //added fornot otp user applicant OR u.phone1 = $username1<Revathi>
        $username1=substr("$username",2);
        $sql = "SELECT u.* FROM {user} u WHERE u.username = ?  OR u.phone1 = $username1 AND u.confirmed = 1 ";
        $validusers = $DB->get_record_sql($sql, [$username]);

        $appdetails->username = $username;

        if (empty($validusers)) {

              $exsql = "SELECT * FROM {local_otp} op WHERE userid = ? ORDER BY id DESC LIMIT 1 ";
              $data = $DB->get_record_sql($exsql, [$username]);

              $currentdate = time();

              if ($data) {

                $seconds = $this->calculate_time_diffrence($data->timecreated);
                // Otp exist not expired
                if ($seconds['invert'] == 1 && $seconds['seconds'] <= get_config('auth_otp', 'minrequestperiod')) {

                    return 7;

                }else { // Already exist otp but expired

                    $this->old_user_handle($username);
                    $otp = mt_rand(1001, 9999);
                    $msg = $otp;
                    $result = $this->get_curl_options($username, $msg);
                    $appdetails->username = $username;
                    $appdetails->phonenumber = $username;
                    if(empty($result)) {
                      return 0;
                    } else {

                        $text = json_decode($result);

                        if($text->status != "success") {

                          $desc = get_string('errorcodefromservice', 'auth_otp', $appdetails) . $text->status;

                          $this->local_logs('otp', 'Server', 1, $desc, 'Error');

                          $this->local_otp_api_report_logs($result,$username);

                          return 0;
                        }
                    }
                    $this->update_otp($otp, $username, $username, $username);
                    $appdetails->otp = $otp;
                    $desc = get_string('otpsendtomobile', 'auth_otp', $appdetails);

                    $this->local_logs('otp', 'User', 1, $desc, 'Success');

                    $this->local_otp_api_report_logs($result,$username);

                    // Send otp to whatsapp.
                    return 3;
                }

              }else{

                  $otp = mt_rand(1001, 9999);
                  $msg = $otp;
                  $result = $this->get_curl_options($username, $msg);
                  $appdetails->username = $username;
                  $appdetails->phonenumber = $username;
                  if(empty($result)) {
                    return 0;
                  } else {

                      $text = json_decode($result);

                      if($text->status != "success") {

                        $desc = get_string('errorcodefromservice', 'auth_otp', $appdetails) . $text->status;

                        $this->local_logs('otp', 'Server', 1, $desc, 'Error');

                        $this->local_otp_api_report_logs($result,$username);

                        return 0;
                      }
                  }
                  $this->update_otp($otp, $username, $username, $username);
                  $appdetails->otp = $otp;
                  $desc = get_string('otpsendtomobile', 'auth_otp', $appdetails);

                  $this->local_logs('otp', 'User', 1, $desc, 'Success');

                  $this->local_otp_api_report_logs($result,$username);

                  // Send otp to whatsapp.
                  return 3;
              }

        }else{

          $desc=get_string('nototpapplicant', 'auth_otp', $appdetails);
          $this->local_logs('otp', 'User', 1, $desc, 'warning');
          return 5;

        }

      // $desc=get_string('notvalidapplicant', 'auth_otp', $appdetails);
      // $this->local_logs('otp', 'User', 1, $desc, 'warning');
		  // return 1;
	  } else if (empty($validusers->phone1)) {
		  $appdetails->username = $username;
      $appdetails->phonenumber = $validusers->phone1;
      $desc=get_string('notvalidphone', 'auth_otp', $appdetails);
      $this->local_logs('otp', 'User', 1, $desc, 'warning');
		  return 2;
	  } else {
      $otp = mt_rand(1001, 9999);
      $msg = $otp;
      $result = $this->get_curl_options($phonenumber, $msg);

      $appdetails->username = $validusers->username;
      $appdetails->phonenumber = $phonenumber;
      if(empty($result)) {
        return 0;
      } else {
          $text = json_decode($result);

          if($text->status != "success") {

            $desc = get_string('errorcodefromservice', 'auth_otp', $appdetails) . $text->status;

            $this->local_logs('otp', 'Server', 1, $desc, 'Error');

            $this->local_otp_api_report_logs($result,$phonenumber);

            return 0;
          }
      }

      $this->update_otp($otp, $phonenumber, $username, $validusers->id);
      $appdetails->otp = $otp;
      $desc = get_string('otpsendtomobile', 'auth_otp', $appdetails);

      $this->local_logs('otp', 'User', 1, $desc, 'Success');

      $this->local_otp_api_report_logs($result,$phonenumber);

      // Send otp to whatsapp.
      return 3;
		}
    exit;
  }

	public function validate_otp($username, $otp) {
		global $DB, $CFG;
    $sql = "SELECT u.id,u.username,u.email,u.phone1 FROM {user} u WHERE u.username = ? AND u.auth = 'otp' ";
    $validusers = $DB->get_record_sql($sql, [$username]);

    $sql = "SELECT * FROM {local_otp} op WHERE op.userid = ?  AND op.otpcode = ? ORDER BY id DESC LIMIT 1 ";

    $validinfo = $DB->get_record_sql($sql, [$validusers->id, $otp]);
    $appdetails = new stdClass();
		if (!empty($validinfo)) {
	    if ($validinfo->trystatus > 3) {
		    $appdetails->username = $username;
			  $appdetails->otp = $otp;
			  $desc=get_string('otpabovethree', 'auth_otp', $appdetails);
			  $this->local_logs('otp', 'User', 1, $desc, 'moreotp');
	      return 2;
		  } else if ($validinfo->inuse == 1) {
		    $appdetails->username=$username;
			  $appdetails->otp = $otp;
			  $desc = get_string('incorrectotp', 'auth_otp', $appdetails);
			  $this->local_logs('otp', 'User', 1, $desc, 'warning');
        return 4;
      } else {
				$trystatus = $validinfo->trystatus;
				$otpdetails = new stdClass();
				$otpdetails->id = $validinfo->id;
				$otpdetails->trystatus = ++$trystatus;
				$DB->update_record('local_otp', $otpdetails);

				$appdetails->username = $username;
				$appdetails->otp = $otp;
				$appdetails->trycount = $otpdetails->trystatus;
				$desc=get_string('validotpentered', 'auth_otp', $appdetails);
				$this->local_logs('otp', 'User', 1, $desc, 'Success');
				return 1;
      }
    } else {


      $sql = "SELECT * FROM {local_otp} op WHERE op.userid = ?  AND op.otpcode = ? ORDER BY id DESC LIMIT 1 ";

      $validinfo = $DB->get_record_sql($sql, [$username, $otp]);
      $appdetails = new stdClass();
      if (!empty($validinfo)) {

        $authplugin = get_auth_plugin('otp');
        $user = new stdClass();
        $user->auth = 'otp';
        $user->confirmed = 1;
        $user->firstaccess = 0;
        $user->timecreated = time();
        $user->phone1 = $username;
        $user->username = $username;
        $user->firstname = $username;
        $user->lastname = '';
        $user->password = '';
        $user->mnethostid = 1;
        $user->email = $username . '@otp.com';
        $user->open_freetests = get_config('local_users', 'freetests');
        $user->open_costcenterid = get_config('local_costcenter','defaultschool');

        $userid=$authplugin->create_user($user);

        $otpdetails = new stdClass();
        $otpdetails->otpcode = $otp;
        $otpdetails->phonenumber = $username;
        $otpdetails->username = $username;
        $otpdetails->userid = $userid;
        $otpdetails->timecreated = time();

        $exsql = "SELECT * FROM {local_otp} op WHERE userid = ? AND inuse = 0 AND trystatus < 3 ORDER BY id DESC LIMIT 1 ";
        $checkexist = $DB->get_record_sql($exsql, [$username]);

        if ($checkexist) {
          $otpdetails->id = $checkexist->id;
          $otpdetails->trystatus = 0;
          $otpdetails->timemodified = time();
          $DB->update_record('local_otp', $otpdetails);
        }

        return $this->validate_otp($username,$otp);

      }else{

          $sql = "SELECT * FROM {local_otp} op WHERE op.username = ? ORDER BY id DESC LIMIT 1 ";
          $validinfo = $DB->get_record_sql($sql, [$username]);
          $trystatus = $validinfo->trystatus;
          $otpdetails = new stdClass();
          $otpdetails->id = $validinfo->id;
          $otpdetails->trystatus = ++$trystatus;
          $DB->update_record('local_otp', $otpdetails);
          if($trystatus > 3){
            $appdetails->username = $username;
            $appdetails->otp = $otp;
            $desc=get_string('otpabovethree', 'auth_otp', $appdetails);
            $this->local_logs('otp', 'User', 1, $desc, 'warning');
            return 2;
          } else {
            $appdetails->username = $username;
            $appdetails->otp = $otp;
            $desc = get_string('otpnotvalid', 'auth_otp', $appdetails);
            $this->local_logs('otp', 'User', 1, $desc, 'warning');
            return 3;
          }
      }
    }
  }

  public function send_otp_touser($username) {
		global $DB, $CFG;
		$appdetails = new stdClass();

    $sql = "SELECT u.id, u.username, u.email, u.phone1 FROM {user} u WHERE u.username = ?  AND u.confirmed = 1 AND u.auth = 'otp' ";
    $validusers = $DB->get_record_sql($sql, [$username]);

    $phonenumber = preg_replace( '/[^0-9]/', '', $validusers->phone1 );
    $phonelength = strlen($phonenumber);

    $appdetails->username = $username;
    //check if user is valid or not.
    if (empty($validusers)) {
      // $desc = get_string('notvalidapplicant', 'auth_otp', $appdetails);
      // $this->local_logs('otp', 'User', 1, $desc, 'warning');
      // return 1;


      $otp = mt_rand(1001, 9999);
      $msg = $otp;
      $result = $this->get_curl_options($username, $msg);

      $appdetails->username = $username;
      $appdetails->phonenumber = $username;
      if(empty($result)) {
        return 0;
      } else {

        $text = json_decode($result);

        if ($text->status != "success") {

          $desc = get_string('errorcodefromservice', 'auth_otp', $appdetails). $text->status;

          $this->local_logs('otp', 'Server', 1, $desc, 'Error');

          $this->local_otp_api_report_logs($result,$username);


          return 0;
        }
      }

      $this->update_otp($otp, $username, $username, $username);

      $appdetails->otp = $otp;
      $desc = get_string('otpsendtomobile', 'auth_otp', $appdetails);

      $this->local_logs('otp', 'User', 1, $desc, 'Success');

      $this->local_otp_api_report_logs($result, $username);

      return 3;

    } else if (empty($validusers->phone1)) {

      $appdetails->phonenumber = $validusers->phone1;
      $desc = get_string('notvalidphone', 'auth_otp', $appdetails);
      $this->local_logs('otp', 'User', 1, $desc, 'warning');
      return 2;
    } else {
      $otp = mt_rand(1001, 9999);
      $msg =  $otp;
      $result = $this->get_curl_options($phonenumber, $msg);

      $appdetails->username = $validusers->username;
      $appdetails->phonenumber = $phonenumber;
      if(empty($result)) {
        return 0;
      } else {

        $text = json_decode($result);

        if ($text->status != "success") {

          $desc = get_string('errorcodefromservice', 'auth_otp', $appdetails). $text->status;

          $this->local_logs('otp', 'Server', 1, $desc, 'Error');

          $this->local_otp_api_report_logs($result,$phonenumber);

          return 0;

        }
      }

      $this->update_otp($otp, $phonenumber, $username, $validusers->id);

      $appdetails->otp = $otp;
      $desc = get_string('otpsendtomobile', 'auth_otp', $appdetails);

      $this->local_logs('otp', 'User', 1, $desc, 'Success');

      $this->local_otp_api_report_logs($result, $phonenumber);

      // Send otp to whatsapp.
      return 3;
    }
    exit;
  }

  private function get_curl_options($mobile, $msg) {

    // $response=json_encode(array('result'=>1));

    // return $response;

    $url = $this->apiurl;

    $apiKey = urlencode($this->token);

    $numbers = array($mobile);

    $numbers = implode(',', $numbers);

    $message = str_replace("[otp]", "$msg", $this->templateid);

    $sender = urlencode($this->websiteparam);

    $params = array('apikey' => $apiKey, 'numbers' => $numbers, 'sender' => $sender, 'message' => $message);


    $ch = curl_init($this->apiurl);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
  }

  private function update_otp($otp, $phonenumber, $username, $userid) {
    global $DB;
    $otpdetails = new stdClass();
    $otpdetails->otpcode = $otp;
    $otpdetails->phonenumber = $phonenumber;
    $otpdetails->username = $username;
    $otpdetails->userid = $userid;
    $otpdetails->timecreated = time();

    $exsql = "SELECT * FROM {local_otp} op WHERE userid = ? AND inuse = 0 AND trystatus < 3 ORDER BY id DESC LIMIT 1 ";
    $checkexist = $DB->get_record_sql($exsql, [$userid]);

    if ($checkexist) {
      $otpdetails->id = $checkexist->id;
      $otpdetails->trystatus = 0;
      $otpdetails->timemodified = time();
      $DB->update_record('local_otp', $otpdetails);
    } else {
      $DB->insert_record('local_otp', $otpdetails);
    }
  }

  private function send_otp_email($otp, $validusers) {
    global $DB;
    $messagehtml = "";
    $subject = "OTP for Login to SeekoG";
    $message = "Your One Time Password for Login to SeekoG is $otp.
    Please use the password to complete the verification. Please do not share this with anyone.
    - Team SeekoG
    ";
    $supportuser = $DB->get_record('user', ['id' => 2]);
    email_to_user($validusers, $supportuser, $subject, $message, $messagehtml);
  }

  /*
   * @method local_logs Get logs
   * @param $event
   * @param $module
   * @param $moduleid
   * @param $description
   * @param $type
   * @output data will be insert into mdl_local_logs table
   */
  function local_otp_api_report_logs($data,$phonenumber){
      global $DB, $USER, $CFG;

      $objdata=json_decode($data,true);


      $status=$objdata['status'];

      $log_data = new stdClass();
      $log_data->phonenumber = $phonenumber;
      $log_data->status = $status;
      $log_data->reason = ($status != 'success') ? json_encode($objdata['errors']) : '';
      $log_data->serviceresponse = $data;
      $log_data->submittedtime = time();

      $id=$DB->insert_record('local_otp_api_report', $log_data);

  }
  /**
     * Calculate time diffrence between otp generated time to current time
     * @param $otpcreated
     * @return array
     * @throws Exception
  */
  public function calculate_time_diffrence($otpcreated){
        $start = new DateTime(date("Y-m-d H:i:s"));
        $end = new DateTime(date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($otpcreated))));
        $diff = $end->diff($start);
        $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
        $hoursInSecs = $diff->h * 60 * 60;
        $minsInSecs = $diff->i * 60;
        $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
        return ['invert' => $diff->invert, 'seconds' => $seconds];
  }
  /**
     * Update Old user otp token
     *
     * @param $userid
     * @throws dml_exception
  */
  public function old_user_handle($userid){
        global $DB;
        $currentdate = time();
        $data = $DB->execute("UPDATE {local_otp} SET inuse=-1 ,timemodified = ".$currentdate ."  where userid = " . $userid . " ORDER BY id DESC LIMIT 1");

  }
  private function get_report_curl_options($pushid) {

    //--API PARAMETERS ENCODED AS JSON--

    //https://help.pickyassist.com/api-documentation-v2/push-api/api-variables

    $postfields = array(
      'token' => $this->token,
      'template_id' => $pushid
    );

    $JSON_DATA=json_encode($postfields);


    $ch = curl_init($this->reportapiurl);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($JSON_DATA))
      );

    $result = curl_exec($ch);

    return $result;
  }
}
