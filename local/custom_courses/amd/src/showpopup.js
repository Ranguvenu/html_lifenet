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
 * User allocation module
 *
 * @module     local_custom_courses/showpopup
 * @package
 * @copyright  2024 Moodle India Information Solutions Pvt Ltd
 * @author     2024 Shamala <shamala.kandula@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import $ from 'jquery';
import * as Notification from 'core/notification';
import * as Str from 'core/str';

/**
 * Displays a modal form to userrole info
 *
 * @param {string} message
 */
const showpopup = function(message) {
    var reason = message;
    Str.get_strings([
        {
            key: 'ok',
            component: 'local_custom_courses',
        },
        {
            key: 'confirm',
            component: 'local_custom_courses',
        },
    ]).then(function(s) {
        Notification.confirm(s[2], reason, s[0], s[1], function() {
        });
    }).fail(Notification.exception);
};
/**
 * Initialise user creation
 */
export const init = () => {
    //showpopup();
    var checkurl = window.location.pathname.split("/");
    if (checkurl.includes('course') || checkurl.includes('mod')) {
        setInterval(function(){
            $.ajax({
                url: M.cfg.wwwroot + '/local/custom_courses/checkcompletion.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.completed) {
                        showpopup(response.message);
                    }
                }
            });
        },1000);
    }
};
/**
 * Handling the certificates dropdown in courses
 *
 */
export const certificate_dropdown = () => {
    $("select[name='certificateid'] option:selected").prop('selected', false);
    $("select[name='certificateid']").parent().find('.bg-secondary').html('');
};
