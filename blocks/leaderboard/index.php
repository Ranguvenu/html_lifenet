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
 * TODO describe file index
 *
 * @package    block_leaderboard
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$url = new moodle_url('/blocks/leaderboard/index.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->requires->js('/blocks/leaderboard/js/jquery.dataTables.js',true);
$PAGE->requires->js_call_amd('block_leaderboard/leaderboard', 'genericDatatable', array());
$PAGE->requires->css('/blocks/leaderboard/css/jquery.dataTables.min.css');

$PAGE->set_title(get_string('pluginname', 'block_leaderboard'));
$PAGE->set_heading(get_string('pluginname', 'block_leaderboard'));

echo $OUTPUT->header();
$lib = new block_leaderboard\lib();
$data = $lib->getleadreboard();
echo $OUTPUT->render_from_template(
    'block_leaderboard/leaderboard',
    [
        'leaderboarddata' => array_values($data),
        'viewmore' => $hasusers,
    ]
);
echo $OUTPUT->footer();
