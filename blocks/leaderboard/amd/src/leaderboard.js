/**
 *
 * @module     block_leaderboard/NewSubdept
 * @package    local_costcenter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([
    'block_leaderboard/jquery.dataTables',
    'core/str',
    'core/modal_factory',
    'core/fragment',
    'core/modal_events',
    'core/ajax',
    'jquery',
    'jqueryui'
], function (DataTable, Str, ModalFactory, Fragment, ModalEvents, Ajax, $) {
    return users = {
        init: function () {
        },
        genericDatatable: function () {
            $('#leaderboard').DataTable({
                "searching": true,
                //"responsive": true,
                "aaSorting": [],
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "aoColumnDefs": [{ 'bSortable': false, 'aTargets': [0] }],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search",
                    "paginate": {
                        "next": ">",
                        "previous": "<"
                    },
                    zeroRecords: 'No Users Found/Available',
                }
            });
        },        
    };
});