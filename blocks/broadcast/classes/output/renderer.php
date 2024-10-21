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
 * Renderer to get broadcast messages
 *
 * @package   block_broadcast
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_broadcast_renderer extends \plugin_renderer_base {

	/**
     * To get the list of broadcast messages.
     *
     * @param object $user
     * @return string.
     */
    function get_broadcast_messages() {
        global $USER, $OUTPUT, $DB;
        $systemcontext = context_system::instance();
        $params = [];

        $sql = "SELECT *
                  FROM {block_broadcast_messages}
                  WHERE 1=1 ";

        if (!is_siteadmin()) {
            if (!$USER->country) {
                return null;
            }
            $sql .= " AND country = :country ";
            $params['country'] = $USER->country;
            $motivationmessage = $DB->get_record_sql($sql, $params);
        } else {
            $messages = $DB->get_records_sql($sql, $params);
            $countries = get_string_manager()->get_list_of_countries();

            $i = 1;
            foreach ($messages as $key => $value) {
                $value->country = $countries[$value->country];
                $value->message = html_to_text($value->message, '', false);
                $value->count = $i;
                $i++;
            }
        }

        $admin = is_siteadmin() ? true : false;

        return  $OUTPUT->render_from_template(
            'block_broadcast/index',
            [
                'messages' => $admin ? array_values($messages) : [],
                'admin' => $admin,
                'motivationmessage' => $motivationmessage->message,
            ]
        );
    }
}
