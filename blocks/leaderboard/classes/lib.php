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

namespace block_leaderboard;

/**
 * Class lib
 *
 * @package    block_leaderboard
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lib {
    
    public function getusers($limit = 0) {
        global $DB, $USER, $OUTPUT, $CFG;

        $country = (!is_siteadmin()) ? $USER->country : null;

        $sql = "SELECT x.userid, u.id, u.firstname, u.lastname, u.email, SUM(x.xp) as xp 
                  FROM {block_xp} x 
                  JOIN {user} u ON x.userid = u.id ";

        if ($country) {
            $sql .= " AND u.country = :country ";
        }

        $sql .= " GROUP BY x.userid 
                  ORDER BY xp DESC ";

        if ($limit) {            
            $sql .= " LIMIT $limit ";
            $viewmoreurl = $CFG->wwwroot . '/blocks/leaderboard/index.php';
        } else {
            $viewmoreurl = null;
        }

        $records = $DB->get_records_sql($sql, ['country' => $country]);

        return $records;
    }

    public function getleadreboard($limit = 0) {
        global $PAGE, $USER, $OUTPUT, $CFG;

        $users = $this->getusers($limit);

        $xprenderer = $PAGE->get_renderer('block_xp');

        $i = 0;
        foreach ($users as $user) {
            $user->fullname = fullname($user);
            $user->profileimageurl = $OUTPUT->user_picture($user, array('class' => 'rounded-circle user_img', 'link' => false));
            $user->points = $xprenderer->xp_human($user->xp);
            $user->rank = ++$i;
        }

        return $users;
    }
}
