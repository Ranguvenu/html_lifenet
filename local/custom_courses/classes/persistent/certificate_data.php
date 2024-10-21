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

namespace local_custom_courses\persistent;

 use core\persistent;
/**
 * Class certificate_data
 *
 * @package   local_custom_courses
 * @copyright
 * @author
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class certificate_data extends persistent {
    /**
     * Database table.
     */
    public const TABLE = 'custom_courses_certificate_data';
    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties(): array {
        return [               
                'courseid' => [
                    'type' => PARAM_INT,
                    ],
                'templateid' => [
                    'type' => PARAM_INT,
                    'optional' => false,
                    'default' => null,
                    'null' => NULL_ALLOWED,
                    ],
                ];
    }
    

}
