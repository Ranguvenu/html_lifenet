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
 * CSS pre-processor to let us replace placeholders with URLs, for overriding icons in the mobile app.
 *
 * @package    block_statistics
 */

define('ABORT_AFTER_CONFIG', true);
require_once(__DIR__ . '/../../config.php');
$css = file_get_contents($CFG->dirroot . '/blocks/statistics/mobile.css');
$css = str_replace('@@WWWROOT@@', $CFG->wwwroot, $css);
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/css');
echo $css;
