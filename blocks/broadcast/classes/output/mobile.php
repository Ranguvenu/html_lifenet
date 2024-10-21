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
 * Mobile output class for broadcast
 *
 * @package     block_broadcast
 * @copyright   2022 Daniel Thies <dethies@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_broadcast\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/blocklib.php');
require_once($CFG->dirroot . '/admin/tool/mobile/lib.php');

use stdClass;

/**
 * Mobile output class for Deft response block
 *
 * @package     block_broadcast
 * @copyright   2022 Daniel Thies <dethies@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {
    /**
     * Returns the video time course view for the mobile app.
     * @param array $args Arguments from tool_mobile_get_content WS
     *
     * @return array       HTML, javascript and otherdata
     * @throws \required_capability_exception
     * @throws \coding_exception
     * @throws \require_login_exception
     * @throws \moodle_exception
     */
    public static function view_motivation($args) {
        global $CFG, $DB, $PAGE, $OUTPUT, $USER;


        $data = new \stdClass();

        $data->contextlevel = $args['contextlevel'];

        $message = $DB->get_record_sql('SELECT * FROM {block_broadcast_messages} WHERE country = :country', array('country' => $USER->country));

        $data->message = $message;

        $html = $OUTPUT->render_from_template('block_broadcast/mobile/latest/motivation', $data);

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => '<div>'.$html.'</div>',
                ],
            ],
            // 'javascript' => self::template_js(),
            'otherdata' => [
                // 'contextid' => $data->contextid,
            ],
        ];
    }

    /**
     * Return the js for template
     *
     * @return string Javascript
     */
    public static function template_js(): string {
        global $CFG;
        // return file_get_contents($CFG->dirroot . "/blocks/courselibrary/mobileapp/courselibrary.js");
    }



    /**
     * Add venue js library
     *
     * @param array $args Arguments from tool_mobile_get_content WS
     */
    public static function init($args): array {
        global $CFG;

        $js = "";

        return [
            'javascript' => $js,
        ];
    }
}
