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
 * No authentication plugin upgrade code
 *
 * @package    auth_otp
 * @copyright  2017 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade auth_otp.
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_auth_otp_upgrade($oldversion) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    // Automatically generated Moodle v3.3.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.4.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.5.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.6.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.7.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.8.0 release upgrade line.
    // Put any upgrade step following this.
    if ($oldversion < 2022122900.05) {

        // Define table local_otp_api_report to be created.
        $table = new xmldb_table('local_otp_api_report');

        // Adding fields to table local_otp_api_report.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('phonenumber', XMLDB_TYPE_CHAR, '250', null, null, null,null);
        $table->add_field('status', XMLDB_TYPE_CHAR, '250', null, null, null,null);
        $table->add_field('reason', XMLDB_TYPE_CHAR, '250', null, null, null,null);
        $table->add_field('serviceresponse', XMLDB_TYPE_TEXT, 'big', null, null, null,null);
        $table->add_field('submittedtime', XMLDB_TYPE_CHAR, '250', null, null, null,null);

        // Adding keys to table local_otp_api_report.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_otp_api_report.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2022122900.05, 'auth', 'otp');
    }

    return true;
}
