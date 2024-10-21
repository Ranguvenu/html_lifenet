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
 * Mobile output class for Deft response block
 *
 * @package     block_learnerscript
 * @copyright   2022 Daniel Thies <dethies@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_learnerscript\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/blocklib.php');

use block_learnerscript\lessons as lessons;
use stdClass;


/**
 * Mobile output class for dashboardcourses response block
 *
 * @package     block_learnerscript
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
    public static function enrolledlessons($args) {
        global $CFG, $DB, $PAGE, $OUTPUT, $USER;

        $args = (object) $args;
        $foldername = $args->appversioncode >= 3950 ? 'latest/' : 'ionic3/';

        $output = $PAGE->get_renderer('block_learnerscript');

        $lessons = new lessons();
        $start = 0;
        $limit = 5;
        $lessonsdata = $lessons->mylessonsinfo('', $start, $limit);

        $html = $OUTPUT->render_from_template('block_learnerscript/mobile/'. $foldername . 'lessons', $data);

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $html,
                ],
            ],
            'javascript' => self::template_js($args),
            'otherdata' => [
                'hasMoreLessons' => $lessonsdata['total'] > $limit,
                'total' => $lessonsdata['total'],
                'lessons' => json_encode(array_values($lessonsdata['lessons']))
            ],
        ];
    }

    /**
     * Return the js for template
     *
     * @return string Javascript
     */
    public static function template_js($args): string {
        global $CFG;

        $args = (object)$args;
        $foldername = $args->appversioncode >= 3950 ? 'latest/' : 'ionic3/';

        return file_get_contents($CFG->dirroot . "/blocks/lessons/mobileapp/" . $foldername . "lessons.js");
    }

    /**
     * Add venue js library
     *
     * @param array $args Arguments from tool_mobile_get_content WS
     */
    public static function init($args): array {
        global $CFG;

        $args = (object)$args;
        $foldername = $args->appversioncode >= 3950 ? 'latest/' : 'ionic3/';
        return [
            'javascript' => file_get_contents($CFG->dirroot . '/blocks/lessons/mobileapp/' . $foldername . 'init.js'),
        ];
    }

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
    public static function completedlessons($args) {
        global $CFG, $DB, $PAGE, $OUTPUT, $USER;

        $args = (object) $args;
        $foldername = $args->appversioncode >= 3950 ? 'latest/' : 'ionic3/';

        $output = $PAGE->get_renderer('block_lessons');

        $start = 0;
        $limit = 10;

        $lessons = new lessons();
        $lessonsdata = $lessons->mylessonsinfo('', $start, $limit);

        $html = $OUTPUT->render_from_template('block_lessons/mobile/'. $foldername . 'alllessons', $data);
        $pageurl = new \moodle_url('/blocks/lessons/courses.php');
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $html,
                ],
            ],
            'javascript' => self::template_js($args),
            'otherdata' => [
                'pageurl' => $pageurl->out(false),
                'hasMoreLessons' => $lessonsdata['total'] > $limit,
                'total' => $lessonsdata['total'],
                'lessons' => json_encode(array_values($lessonsdata['lessons']))
            ],
        ];
    }    

}
