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
 * Functionality for Broadcast
 *
 * @module     local_resources/resources
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import 'local_resources/jquery.dataTables';

/**
 * Initialise broadcast actions
 */
export const init = () => {
    $(document).ready(function() {
        $('#resources').DataTable({
            retrieve: true,
            bInfo : false,
            lengthMenu: [5, 10, 25, 50],
            language: {
                emptyTable: "No courses/resource activities found",
                paginate: {
                    previous: "<",
                    next: ">"
                },
                zeroRecords: 'No courses/resource activities found',
            },
        });
    });
};
