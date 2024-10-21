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

namespace block_broadcast;

/**
 * Class api
 *
 * @package    block_broadcast
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class api {

    /**
     * Create broadcast instance
     *
     * @param  [object] $data
     *
     * @return [object]
     */
    public static function broadcast_instance($data) {
        global $USER, $DB;
        try {
            $record = new \stdClass();
            $record->country = $data->country;
            $record->message = $data->message['text'];
            $record->usercreated = $USER->id;
            if ($data->id > 0) {
                $record->id = $data->id;
                $record->timeupdated = time();
                $id = $DB->update_record('block_broadcast_messages', $record);
            } else {
                $record->timecreated = time();
                $id = $DB->insert_record('block_broadcast_messages', $record);
            }
            return $id;
        } catch (\Exception $e) {
            throw new \moodle_exception("Error in creation/updation");
        }
    }

    /**
     * Delete broadcast instance
     *
     * @param  [object] $data
     *
     * @return [object]
     */
    public static function delete_instance($id) {
        global $DB;
        try {
            if ($DB->record_exists('block_broadcast_messages', ['id' => $id])) {
                $DB->delete_records('block_broadcast_messages', ['id' => $id]);
                return true;
            }
        } catch (\Exception $e) {
            throw new \moodle_exception("Error in deleting");
            return false;
        }
    }

    /**
     * Function is used to get specific broadcast record
     *
     * @param $id
     * @return object
     */
    public static function broadcast_records($id) {
        global $DB;
        try {
            if ($DB->record_exists('block_broadcast_messages', ['id' => $id])) {
                return $DB->get_record('block_broadcast_messages', ['id' => $id]);
            }
        } catch (\Exception $e) {
            throw new \moodle_exception("Error in fetching");
        }
    }
}
