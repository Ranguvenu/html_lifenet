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
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_prisemforce
 * @copyright   2024 Moodle India Information Solutions Pvt Ltd
 * @author      2024 Shamala <shamala.kandula@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
use cache;

function local_custom_courses_before_footer() {
    global $PAGE;
    // Initialize the cache
    //$cache = cache::make('local_custom_courses', 'mycache');
    // $cacheddata = $cache->get('coursecompleted');
    // if ($cacheddata !== false) {
        // Data was found in the cache
        if (!is_siteadmin()) {
            $PAGE->requires->js_call_amd('local_custom_courses/showpopup', 'init');
        }
        
        // Purge all cached items in the definition
        //$cache->purge();
    //}
        
}
function local_custom_courses_extend_navigation(global_navigation $nav) {
    local_custom_courses_before_footer();
}
