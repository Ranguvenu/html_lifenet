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
 * option selector module.
 *
 * @module     local_custom_courses/form-option-selector
 * @class      form-option-selector
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {

    return /** @alias module:tool_lp/form-option-selector */ {

        processResults: function(selector, results) {
            var options = [];
            $.each(results.data, function(index, response) {
                options.push({
                    value: response.id,
                    label: response.name
                });
            });
            return options;
        },

        transport: function(selector, query, callback) {
            var el = $(selector),
            parententity = [],
            type = el.data('type');
            switch(type) {
                case 'certificate_list':
                    var category = $("select[name='category']").val();
                    parententity = {cagegoryid : category};
                break;
            }
            parententity = JSON.stringify(category);
            Ajax.call([{
                methodname: 'local_custom_courses_form_option_selector',
                args: {query:query,type: type,conditions:parententity}
            }])[0].then(callback).catch(Notification.exception);
        }

    };

});
