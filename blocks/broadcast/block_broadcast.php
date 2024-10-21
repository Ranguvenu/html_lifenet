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
 * Form for editing broadcast block instances.
 *
 * @package   block_broadcast
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_broadcast extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_broadcast');
    }

    function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Are you going to allow multiple instances of each block?
     * If yes, then it is assumed that the block WILL USE per-instance configuration
     * @return boolean
     */
    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $PAGE;

        $this->page->requires->js_call_amd('block_broadcast/broadcast', 'init', []);
        $this->page->requires->css('/blocks/broadcast/css/jquery.dataTables.min.css');

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $renderer = $PAGE->get_renderer('block_broadcast');
        $this->content->text = $renderer->get_broadcast_messages();

        return $this->content;
    }
}
