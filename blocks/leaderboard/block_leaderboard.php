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
 * @package    block_leaderboard
 * @copyright  2024 Moodle India Information Solutions Pvt Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class block_leaderboard extends block_base {

    function init() {
        $systemcontext = context_system::instance();
        $this->title = get_string('pluginname', 'block_leaderboard');
    }

    function instance_allow_multiple() {
        return false;
    }

    function hide_header() {
        return false;
    }
    function get_content() {
        global $PAGE, $OUTPUT;
        if ($this->content !== NULL) {
            return $this->content;
        }
        $lib = new block_leaderboard\lib();

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';
        $data = $lib->getleadreboard(10);
        $users = $lib->getusers();
        $hasusers = count($users) > 10 ? true : false;
        $this->content->text .= $OUTPUT->render_from_template(
            'block_leaderboard/leaderboard',
            [
                'leaderboarddata' => array_values($data),
                'viewmore' => $hasusers,
            ]
        );
        return $this->content;
    }
}
