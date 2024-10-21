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
 * Upgrade code for popup message processor
 *
 * @package   message_popup
 * @copyright 2008 Luis Rodrigues
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

/**
 * Upgrade code for the popup message processor
 *
 * @param int $oldversion The version that we are upgrading from
 */
function xmldb_message_popup_upgrade($oldversion) {
    global $DB;
	$dbman = $DB->get_manager();
    // Automatically generated Moodle v4.1.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v4.2.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v4.3.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v4.4.0 release upgrade line.
    // Put any upgrade step following this.
    if ($oldversion <  2024042200.01) {
        $table = new xmldb_table('messages');
        $field = new xmldb_field('attachment', XMLDB_TYPE_INTEGER, '10', null, null, null, 0);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $table1 = new xmldb_table('message_media_items');
        if (!$dbman->table_exists($table1)) {
            // Adding fields.
            $table1->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table1->add_field('messageid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table1->add_field('conversationid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table1->add_field('filename', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null);
            $table1->add_field('uplodedfilename', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null);
            $table1->add_field('filesize', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table1->add_field('mimetype', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null);
            $table1->add_field('extension', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null);
            $table1->add_field('filepath', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null);
            $table1->add_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
            $table1->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
            // Adding key.
            $table1->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

            // Create table.
            $dbman->create_table($table1);
        }
        upgrade_plugin_savepoint(true, 2024042200.01, 'message', 'popup');
    }
    return true;
}
