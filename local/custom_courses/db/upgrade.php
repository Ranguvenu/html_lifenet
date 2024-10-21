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
 * @package     local_custom_courses
 * @copyright   2023 Moodle India Information Solutions Pvt Ltd
 * @author      2023 Shamala <shamala.kandula@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
function xmldb_local_custom_courses_upgrade($oldversion)
{
    global $DB;
    $dbman = $DB->get_manager();    
    if ($oldversion < 2024062007) {
        $table1 = new xmldb_table('custom_courses_completion');

        if ($dbman->table_exists($table1)) {
            $dbman->drop_table($table1);
        }

        $table = new xmldb_table('custom_courses_certificate_data');        
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
            $table->add_field('templateid', XMLDB_TYPE_INTEGER, '10', null, null, null);                  
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null);         
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2024062007, 'local', 'custom_courses');
    }
    if ($oldversion < 2024062010) {
        $table = new xmldb_table('custom_courses_certificate_data');
        $field = new xmldb_field('templateid');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', null, null, null, null);
            if ($dbman->field_exists($table, $field)) {
                $dbman->change_field_type($table, $field);
            }
        upgrade_plugin_savepoint(true, 2024062010, 'local', 'custom_courses');
    }
    return true;
}
