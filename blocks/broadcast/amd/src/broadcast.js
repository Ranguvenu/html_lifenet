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
 * @module     block_broadcast/Broadcast
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import * as Str from 'core/str';
import Notification from 'core/notification';
import Ajax from 'core/ajax';
import 'block_broadcast/jquery.dataTables';

var SERVICES = {
    DELETE_BROADCAST_SERVICES: 'block_broadcast_delete_broadcast',
};

/**
 * broadcast delete
 *
 * @param {object} args
 */
export const deleteModal = (args) => {
    var id = args.id;
    var actionstatusmsg = Str.get_string('confirmdelete', 'block_broadcast');
    Str.get_strings([
        {key: 'confirm'},
        {key: 'cancel'},
    ]).then(function(s) {
        Notification.confirm(s[0], actionstatusmsg, s[0], s[1], function() {
            var promises = Ajax.call([
                {methodname: SERVICES.DELETE_BROADCAST_SERVICES, args: {id: id}}
            ]);
            promises[0].done(function() {
                window.location.reload();
            }).fail(function() {
                // Exception.
            });
        });
    }).fail(Notification.exception);
};

/**
 * Initialise broadcast actions
 */
export const init = () => {
    $(document).ready(function() {
        $('#broadcast').DataTable({
            retrieve: true,
            bInfo : false,
            lengthMenu: [5, 10, 25, 50],
            language: {
                emptyTable: "No Records Found",
                paginate: {
                    previous: "<",
                    next: ">"
                },
                zeroRecords: 'No motivation message found',
            },
        });
    });
};
