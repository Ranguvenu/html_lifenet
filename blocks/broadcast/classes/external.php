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

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_multiple_structure;
use core_external\external_value;
use block_broadcast\api;

require_once($CFG->libdir . '/externallib.php');

/**
 * Class external
 *
 * @package    block_broadcast
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_broadcast_external extends external_api {

    /**
     * Parameters for the delete_broadcast.
     *
     * @return external_function_parameters
     */
    public static function delete_broadcast_parameters() {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'broadcast id', 0),
            ]
        );
    }

    /**
     * Functionality to delete_broadcast
     *
     * @param  [int] $id, id to delete_broadcast
     * @return [boolean]     [true for success]
     */
    public static function delete_broadcast($id) {
        self::validate_parameters(
            self::delete_broadcast_parameters(),
            ['id' => $id]
        );
        $context = \context_system::instance();
        // From web services we must call validate_context.
        self::validate_context($context);

        if ($id) {
            return api::delete_instance($id);
        } else {
            throw new \moodle_exception('Error in deleting');
            return false;
        }
    }

    /**
     * Return parameters for delete_broadcast.
     *
     * @return [external value] [boolean]
     */
    public static function delete_broadcast_returns() {
        return new external_value(PARAM_BOOL, 'return');
    }
}
