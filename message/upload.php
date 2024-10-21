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
 * Accept uploading files by web service token to the user draft file area.
 *
 * POST params:
 *  token => the web service user token (needed for authentication)
 *  filepath => file path (where files will be stored)
 *  [_FILES] => for example you can send the files with <input type=file>,
 *              or with curl magic: 'file_1' => '@/path/to/file', or ...
 *  itemid   => The draftid - this can be used to add a list of files
 *              to a draft area in separate requests. If it is 0, a new draftid will be generated.
 *
 * @package    core_webservice
 * @copyright  2011 Dongsheng Cai <dongsheng@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * AJAX_SCRIPT - exception will be converted into JSON
 */
define('AJAX_SCRIPT', true);

/**
 * NO_MOODLE_COOKIES - we don't want any cookie
 */
define('NO_MOODLE_COOKIES', true);

require_once(__DIR__ . '/../config.php');
require_once($CFG->dirroot . '/webservice/lib.php');

// Allow CORS requests.
header('Access-Control-Allow-Origin: *');
echo $OUTPUT->header();

$fs = get_file_storage();
$totalsize = 0;
$files = array();
foreach ($_FILES as $fieldname => $uploadedfile) {
    // Check upload errors.
    if (!empty($_FILES[$fieldname]['error'])) {
        switch ($_FILES[$fieldname]['error']) {
            case UPLOAD_ERR_INI_SIZE:
                throw new moodle_exception('upload_error_ini_size', 'repository_upload');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                throw new moodle_exception('upload_error_form_size', 'repository_upload');
                break;
            case UPLOAD_ERR_PARTIAL:
                throw new moodle_exception('upload_error_partial', 'repository_upload');
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new moodle_exception('upload_error_no_file', 'repository_upload');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new moodle_exception('upload_error_no_tmp_dir', 'repository_upload');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                throw new moodle_exception('upload_error_cant_write', 'repository_upload');
                break;
            case UPLOAD_ERR_EXTENSION:
                throw new moodle_exception('upload_error_extension', 'repository_upload');
                break;
            default:
                throw new moodle_exception('nofile');
        }
    }

    // Scan for viruses.
    \core\antivirus\manager::scan_file($_FILES[$fieldname]['tmp_name'], $_FILES[$fieldname]['name'], true);

    $file = new stdClass();
    $file->filename = clean_param($_FILES[$fieldname]['name'], PARAM_FILE);
    // Check system maxbytes setting.
    if (($_FILES[$fieldname]['size'] > 20480)) {
        // Oversize file will be ignored, error added to array to notify
        // web service client.
        throw new moodle_exception('maxbytes', 'error');
    } else {
        $file->filepath = $_FILES[$fieldname]['tmp_name'];
        // Calculate total size of upload.
        $totalsize += $_FILES[$fieldname]['size'];
        // Size of individual file.
        $file->size = $_FILES[$fieldname]['size'];
    }
    // Define the path to your file
    $target_dir = $CFG->dataroot.'/message';
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_dir = $CFG->dataroot.'/message/'.$_REQUEST['conversationid'];
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $filenameparts = pathinfo($file->filename);
    $filenameexplode = explode('.', $file->filename);
    $filename = $filenameexplode[0].'_mid'.$_REQUEST['mid'].'.'.$filenameparts['extension'];
    $target_file = $target_dir .'/'. basename($filename);
    $status = move_uploaded_file($file->filepath, $target_file);
    if($status){
        $messagemediaitems = new stdClass;
        $messagemediaitems->messageid = $_REQUEST['mid'];
        $messagemediaitems->conversationid = $_REQUEST['conversationid'];
        $messagemediaitems->filename = $file->filename;
        $messagemediaitems->uplodedfilename = $filename;
        $messagemediaitems->filesize = $_FILES[$fieldname]['size'];
        $messagemediaitems->mimetype = $_FILES[$fieldname]['type'];
        $messagemediaitems->extension = $filenameparts['extension'];
        $messagemediaitems->filepath = $file->filepath;
        $messagemediaitems->timecreated = time();
        $messagemediaitems->usercreated = $USER->id;
        $id = $DB->insert_record('message_media_items', $messagemediaitems);
    }
    if($id){
        echo $status;
    }
}
