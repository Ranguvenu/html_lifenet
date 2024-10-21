<?php
// This file is part of the tool_certificate plugin for Moodle - http://moodle.org/
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
 * Customcert module upgrade code.
 *
 * @package    paygw_mpesakenya
 * @copyright  2024 Shamala Kandula <shamala.kandula@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Customcert module upgrade code.
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool always true
 */
function xmldb_paygw_mpesakenya_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
    if ($oldversion < 2024080200) {
        $table = new xmldb_table('paygw_mpesakenya');
        $field1 = new xmldb_field('merchantrequestid', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        $field2 = new xmldb_field('checkoutrequestid', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        $field3 = new xmldb_field('mobile', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }
        upgrade_plugin_savepoint(true, 2024080200, 'paygw', 'mpesakenya');
    }
    return true;
}