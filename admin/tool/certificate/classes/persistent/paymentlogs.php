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
 * @package     tool_certificate
 * @copyright   2023 Moodle India Information Solutions Pvt Ltd
 * @author      2023 Shamala <shamala.kandula@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_certificate\persistent;

 use core\persistent;
/**
 * Class paymentlogs
 *
 * @package   tool_certificate
 * @copyright
 * @author
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class paymentlogs extends persistent {
    /**
     * Database table.
     */
    public const TABLE = 'tool_certificate_paymentlogs';
    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties(): array {
        return [               
                'userid' => [
                    'type' => PARAM_INT,
                    ],
                'certificateid' => [
                    'type' => PARAM_INT,
                    ],
                'paymentmethod' => [
                    'type' => PARAM_RAW,
                    ],
                'request' => [
                    'type' => PARAM_TEXT,
                    'optional' => false,
                    'default' => null,
                    'null' => NULL_ALLOWED,
                    ],                     
                'response' => [
                    'type' => PARAM_TEXT,
                    'optional' => false,
                    'default' => null,
                    'null' => NULL_ALLOWED,
                    ],
                ];
    }
    

}
