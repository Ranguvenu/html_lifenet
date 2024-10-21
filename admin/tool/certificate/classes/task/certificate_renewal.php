<?php
/**
 * This file is part of eAbyas
 *
 * Copyright eAbyas Info Solutons Pvt Ltd, India
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author eabyas  <info@eabyas.in>
 */
namespace tool_certificate\task;

use local_learningplan\lib;

class certificate_renewal extends \core\task\scheduled_task{
	public function get_name() {
        return get_string('taskcertificaterenewal', 'tool_certificate');
    }
	public function execute(){
		global $DB;
        $template = new \tool_certificate\template();
        $time = time();
        $sql = "SELECT * FROM {tool_certificate_issues} cs
        WHERE (cs.expires IS NOT NULL AND cs.expires > 0 AND 
        DATE(FROM_UNIXTIME(cs.expires)) = DATE(now() + interval 1 day))";
        $allusers = $DB->get_records_sql($sql);
        foreach ($allusers as $all) {        
            $completed = $template->send_renewal_notification($all);
        }
        
	}

}
