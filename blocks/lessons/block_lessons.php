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
 * List of courses in block.
 *
 * @package   block_lessons
 * @copyright Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_lessons extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_lessons');
    }

    function applicable_formats() {
        return array('all' => true);
    }

    public function get_content() {
        global $OUTPUT;

        $this->content = new stdClass();
        $renderer = $this->page->get_renderer('block_lessons');
        $stable = new \stdClass();
        $stable->start = 0;
        $stable->length = 6;
        $list = $renderer->get_dashboardlessons($stable, null);

        $list['hascourses'] = $list['hascourses'] ?: [];

        if (count($list['hascourses']) > 5 && $this->page->pagetype != 'blocks-lessons-courses') {
            $viewmore = true;
        } else {
            $viewmore = false;
        }

        $data = [
            'courses' => array_values(array_values($list['hascourses'])),
            'viewmore' => $viewmore,
        ];

        $this->content->text .= $OUTPUT->render_from_template(
            'block_lessons/index', $data
        );
        $this->content->footer = '';

        return $this->content;
    }
}
