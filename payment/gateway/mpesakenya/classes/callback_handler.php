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
 * Contains helper class to work with MTN Africa REST API.
 *
 * @package    paygw_mpesakenya
 * @copyright  2023 Medical Access Uganda Limited
 * @author     shamala kandula <shamala.kandula@moodle.in>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_mpesa;

defined('MOODLE_INTERNAL') || die();

class callback_handler {
    public static function handle_callback() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Log the received data
        //file_put_contents('/path/to/your/log/file.log', print_r($data, true), FILE_APPEND);
        
        // Process the callback data
        if (isset($data['ResultCode']) && $data['ResultCode'] == 0) {
            // Handle successful transaction           
            self::process_successful_transaction($data);
        } else {
            // Handle failed transaction           
            self::process_failed_transaction($data);
        }
        
        // Respond to M-Pesa
        header('Content-Type: application/json');
        echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Success']);
        exit;
    }
    
    private static function process_successful_transaction($data) {
        global $DB;
        // Extract and handle transaction details
        $table = 'paygw_mpesakenya';
        $transaction = \paygw_airtelafrica\airtel_helper::array_helper('transaction', $data);
        if ($transaction) {
            $response = $data->Body->stkCallback;
            $callback = $response->CallbackMetadata;
            $merchantrequestid = $response->MerchantRequestID;
            $mobile = ($callback->Item[3]->Name == 'PhoneNumber')?$callback->Item[3]->Value : 0;
            $payrec = $DB->get_record($table, ['merchantrequestid' => $merchantrequestid, 'mobile' => $mobile]);
            if ($payrec) {
                $payrec->transactionid = ($callback->Item[1]->Name == 'MpesaReceiptNumber')?$callback->Item[1]->Value : 0;
                //$payrec->timecompleted = ($callback->Item[2]->Name == 'TransactionDate')?$callback->Item[2]->Value : 0;
                $DB->update_record($table, $payrec);
            }
        }
    }
    
    private static function process_failed_transaction($data) {
        // Extract and handle error details
        
    }
}