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

defined('MOODLE_INTERNAL') || die();

use local_resources\lib;

require_once("$CFG->libdir/resourcelib.php");

/**
 * Renderer for local_resources
 *
 * @package    local_resources
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_resources_renderer extends \plugin_renderer_base {

    /**
     * Function is use to display list of course resources.
     *
     * @return [string] [template]
     */
    public function get_course_resources() {
        global $DB, $USER, $OUTPUT;
        $filterdata = (object)['resources' => true];
        $courses = lib::mycourses(false, $filterdata);

        $i = 1;
        foreach ($courses['hascourses'] as $key => $value) {
            $resources = $this->get_resources($value);
            $value->resources = !empty($resources) ? $resources : '';
            $value->count = $i;
            $i++;
        }

        $courses['hascourses'] = $courses['hascourses'] ? : [];

        return $OUTPUT->render_from_template(
            'local_resources/index',
            ['records' => array_values($courses['hascourses'])],
        );
    }

    /**
     * Function is used to get course resources
     *
     * @param [object] $course
     * @return [string]
     */
    public function get_resources($course) {
        global $DB, $CFG;

        // get list of all resource-like modules
        $allmodules = $DB->get_records('modules', ['visible' => 1]);
        $availableresources = [];
        foreach ($allmodules as $key => $module) {
            $modname = $module->name;
            $libfile = "$CFG->dirroot/mod/$modname/lib.php";
            if (!file_exists($libfile)) {
                continue;
            }

            $archetype = plugin_supports('mod', $modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
            if ($archetype != MOD_ARCHETYPE_RESOURCE) {
                continue;
            }

            $availableresources[] = $modname;
        }

        $modinfo = get_fast_modinfo($course);
        $cms = [];
        foreach ($modinfo->cms as $cm) {
            if (!in_array($cm->modname, $availableresources)) {
                continue;
            }
            // Exclude activities that aren't visible or have no view link (e.g. label). Account for folder being displayed inline.
            if (!$cm->uservisible || (!$cm->has_view() && strcmp($cm->modname, 'folder') !== 0)) {
                continue;
            }
            $cms[$cm->id] = $cm;
        }

        $list = [];
        foreach ($cms as $cm) {
            $data = [];

            $purpose = plugin_supports('mod', $cm->modname, FEATURE_MOD_PURPOSE, 'none');
            $isbranded = component_callback('mod_' . $cm->modname, 'is_branded') !== null ? : false;

            $class = $cm->visible ? '' : 'class=dimmed'; // hidden modules are dimmed
            $url = $cm->url ?: new moodle_url("/mod/{$cm->modname}/view.php", ['id' => $cm->id]);

            $data['resourceurl'] = $url->out();
            $data['iconurl'] = $cm->get_icon_url()->out();
            $data['type'] = $cm->get_module_type_name()->out();
            $data['rname'] = $cm->get_formatted_name();
            $data['class'] = $class;
            $data['extra'] = $extra;
            $data['purpose'] = $purpose;
            $data['branded'] = $isbranded;
            $data['modname'] = $cm->modname;
            $data['instance'] = $cm->instance;
            $data['componentid'] = $cm->id;
            $data['modicon'] = $cm->get_icon_url()->out();
            $data['disabled'] = $cm->visible ? false : true;
            $list[] = $data;
        }

        return $list;        
    }
}
