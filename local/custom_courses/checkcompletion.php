<?php
require_once(__DIR__ . '/../../config.php');
use cache;
use tool_certificate\template;
global $DB, $USER;

$cache = cache::make('local_custom_courses', 'mycache');
$cacheddata = $cache->get('coursecompleted');

$response = 0;
$message = '';
if ($cacheddata !== false) {
    $response = 1;
    if ($cacheddata->certificatename) {
        $message = "Course Completed and issued '$cacheddata->certificatename' certificate.";
        if ($cacheddata->certificatecode) {
            $link = template::view_url($cacheddata->certificatecode);
            $message .= "<a href='$link' download> Download </a>";
        }        
    } else {
        $message = 'Course Completed';
    }    
}
$cache->purge();
if ($response == 1) {
    echo json_encode(array('completed' => true, 'message' => $message));
} else {
    echo json_encode(array('completed' => false, 'message' => $message));
}

