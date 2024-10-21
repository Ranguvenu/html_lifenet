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
 * Strings for component 'group', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   core
 * @copyright 2006 The Open University
 * @author    J.White AT open.ac.uk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addedby'] = 'Added by {$a}';
$string['addgroup'] = 'Add user into community';
$string['addgroupstogrouping'] = 'Add community to grouping';
$string['addgroupstogroupings'] = 'Add/remove communities';
$string['adduserstogroup'] = 'Add/remove users';
$string['allocateby'] = 'Allocate members';
$string['anygrouping'] = '[Any grouping]';
$string['autocreategroups'] = 'Auto-create communities';
$string['backtogroupings'] = 'Back to groupings';
$string['backtogroups'] = 'Back to communities';
$string['badnamingscheme'] = 'Must contain exactly one \'@\' or one \'#\'  character';
$string['byfirstname'] = 'Alphabetically by first name, last name';
$string['byidnumber'] = 'Alphabetically by ID number';
$string['bylastname'] = 'Alphabetically by last name, first name';
$string['createautomaticgrouping'] = 'Create automatic grouping';
$string['creategroup'] = 'Create community';
$string['creategrouping'] = 'Create grouping';
$string['creategroupinselectedgrouping'] = 'Create community in grouping';
$string['createingrouping'] = 'Grouping of auto-created communities';
$string['createorphangroup'] = 'Create orphan community';
$string['csvdelimiter'] = 'CSV separator';
$string['databaseupgradegroups'] = 'Communities version is now {$a}';
$string['defaultgrouping'] = 'Default grouping';
$string['defaultgroupingname'] = 'Grouping';
$string['defaultgroupname'] = 'Community';
$string['deleteallgroupings'] = 'Delete all groupings';
$string['deleteallgroups'] = 'Delete all communities';
$string['deletegroupconfirm'] = 'Are you sure you want to delete community \'{$a}\'?';
$string['deletegrouping'] = 'Delete grouping';
$string['deletegroupingconfirm'] = 'Are you sure you want to delete grouping \'{$a}\'? (Communities in the grouping are not deleted.)';
$string['deletegroupsconfirm'] = 'Are you sure you want to delete the following communities?';
$string['deleteselectedgroup'] = 'Delete';
$string['disablemessagingaction'] = 'Disable messaging';
$string['editgroupingsettings'] = 'Edit grouping settings';
$string['editgroupsettings'] = 'Edit community settings';
$string['editusersgroupsa'] = 'Edit communities for "{$a}"';
$string['enablemessaging'] = 'Community messaging';
$string['enablemessagingaction'] = 'Enable messaging';
$string['enablemessaging_help'] = 'If enabled, community members can send messages to the others in their community via the messaging drawer.';
$string['encoding'] = 'Encoding';
$string['enrolmentkey'] = 'Enrolment key';
$string['enrolmentkey_help'] = 'An enrolment key enables access to the course to be restricted to only those who know the key. If a community enrolment key is specified, then not only will entering that key let the user into the course, but it will also automatically make them a member of this community.

Note: Community enrolment keys must be enabled in the self enrolment settings and an enrolment key for the course must also be specified.';
$string['enrolmentkeyalreadyinuse'] = 'This enrolment key is already used for another community.';
$string['erroraddremoveuser'] = 'Error adding/removing user {$a} to community';
$string['erroreditgroup'] = 'Error creating/updating community {$a}';
$string['erroreditgrouping'] = 'Error creating/updating grouping {$a}';
$string['erroraddtogroup'] = 'Invalid value for addtocommunity. It should be 0 for no community mode or 1 for a new community to be created.';
$string['erroraddtogroupgroupname'] = 'You cannot specify groupname when addtogroup is set.';
$string['errorinvalidgroup'] = 'Error, invalid community {$a}';
$string['errorremovenotpermitted'] = 'You do not have permission to remove automatically-added community member {$a}';
$string['errorselectone'] = 'Please select a single community before choosing this option';
$string['errorselectsome'] = 'Please select one or more communities before choosing this option';
$string['evenallocation'] = 'Note: To keep community allocation even, the actual number of members per community differs from the number you specified.';
$string['eventgroupcreated'] = 'Community created';
$string['eventgroupdeleted'] = 'Community deleted';
$string['eventgroupmemberadded'] = 'Community member added';
$string['eventgroupmemberremoved'] = 'Community member removed';
$string['eventgroupupdated'] = 'Community updated';
$string['eventgroupingcreated'] = 'Grouping created';
$string['eventgroupingdeleted'] = 'Grouping deleted';
$string['eventgroupinggroupassigned'] = 'Community assigned to grouping';
$string['eventgroupinggroupunassigned'] = 'Community unassigned from grouping';
$string['eventgroupingupdated'] = 'Grouping updated';
$string['existingmembers'] = 'Existing members: {$a}';
$string['exportgroupsgroupings'] = 'Download communities and groupings as';
$string['filtergroups'] = 'Filter communities by:';
$string['group'] = 'Community';
$string['groupaddedsuccesfully'] = 'Community {$a} added successfully';
$string['groupaddedtogroupingsuccesfully'] = 'Community {$a->groupname} added to grouping {$a->groupingname} successfully';
$string['groupby'] = 'Auto create based on';
$string['groupdescription'] = 'Community description';
$string['groupinfo'] = 'Info about selected community';
$string['groupinfomembers'] = 'Info about selected members';
$string['groupinfopeople'] = 'Info about selected people';
$string['grouping'] = 'Grouping';
$string['groupingaddedsuccesfully'] = 'Grouping {$a} added successfully';
$string['grouping_help'] = 'A grouping is a collection of communities within a course. If a grouping is selected, students assigned to communities within the grouping will be able to work together.';
$string['groupingsection'] = 'Grouping access';
$string['groupingsection_help'] = 'A grouping is a collection of communities within a course. If a grouping is selected here, only students assigned to communities within this grouping will have access to the section.';
$string['groupingdescription'] = 'Grouping description';
$string['groupingname'] = 'Grouping name';
$string['groupingnameexists'] = 'The grouping name \'{$a}\' already exists in this course, please choose another one.';
$string['groupings'] = 'Groupings';
$string['groupingsonly'] = 'Groupings only';
$string['groupmember'] = 'Community member';
$string['groupmemberdesc'] = 'Standard role for a member of a community.';
$string['groupmembers'] = 'Community members';
$string['groupmemberssee'] = 'See community members';
$string['groupmembersselected'] = 'Members of selected community';
$string['groupmode'] = 'Community mode';
$string['groupmode_groupsseparate_help'] = 'Students are divided into communities and can only see their group\'s work.';
$string['groupmode_groupsvisible_help'] = 'Students are divided into communities, but can see the work of other communities.';
$string['groupmode_help'] = '* No communities
* Separate communities: Students are divided into communities and can only see their group\'s work.
* Visible communities: Students are divided into communities, but can see the work of other communities.

The community mode set at course level is the default mode for all activities. If the community mode is forced at course level, it can\'t be changed in an activity.';
$string['groupmodeforce'] = 'Force community mode';
$string['groupmodeforce_help'] = 'The community mode is enforced for all activities and can\'t be changed in an activity.';
$string['groupmy'] = 'My community';
$string['groupname'] = 'Community name';
$string['groupnameexists'] = 'The community name \'{$a}\' already exists in this course, please choose another one.';
$string['groupnotamember'] = 'Sorry, you are not a member of that community';
$string['groups'] = 'Communities';
$string['groupscount'] = 'Communities ({$a})';
$string['groupsettingsheader'] = 'Communities';
$string['groupsgroupings'] = 'Communities & groupings';
$string['groupsinselectedgrouping'] = 'Communities in:';
$string['groupsnone'] = 'No communities';
$string['groupsonly'] = 'Communities only';
$string['groupspreview'] = 'Communities preview';
$string['groupsseparate'] = 'Separate communities';
$string['groupsvisible'] = 'Visible communities';
$string['grouptemplate'] = 'Community @';
$string['importgroups'] = 'Import communities';
$string['importgroups_help'] = 'Communities may be imported via text file. The format of the file should be as follows:

* Each line of the file contains one record
* Each record is a series of data separated by the selected separator
* The first record contains a list of fieldnames defining the format of the rest of the file
* Required fieldname is groupname
* Optional fieldnames are groupidnumber, description, enrolmentkey, groupingname, enablemessaging';
$string['importgroups_link'] = 'group/import';
$string['includeonlyactiveenrol'] = 'Include only active enrolments';
$string['includeonlyactiveenrol_help'] = 'If enabled, suspended users will not be included in communities.';
$string['javascriptrequired'] = 'This page requires JavaScript to be enabled.';
$string['members'] = 'Members per community';
$string['membersofselectedgroup'] = 'Members of:';
$string['namingscheme'] = 'Naming scheme';
$string['namingscheme_help'] = 'The at symbol (@) may be used to create communities with names containing letters. For example Community @ will generate communities named Community A, Community B, Community C, ...

The hash symbol (#) may be used to create communities with names containing numbers. For example Community # will generate communities named Community 1, Community 2, Community 3, ...';
$string['newgrouping'] = 'New grouping';
$string['newpicture'] = 'New picture';
$string['newpicture_help'] = 'Select an image in JPG or PNG format. The image will be cropped to a square and resized to 100x100 pixels.';
$string['noallocation'] = 'No allocation';
$string['nogrouping'] = 'No grouping';
$string['nogroup'] = 'No community';
$string['nogrouping'] = 'No grouping';
$string['nogroups'] = 'There are no communities set up in this course yet';
$string['nogroupsassigned'] = 'No communities assigned';
$string['nopermissionforcreation'] = 'Can\'t create community "{$a}" as you don\'t have the required permissions';
$string['nosmallgroups'] = 'Prevent last small community';
$string['notingroup'] = 'Ignore users in communities';
$string['notingrouping'] = 'Not in a grouping';
$string['notingrouplist'] = 'Not in a community';
$string['nousersinrole'] = 'There are no suitable users in the selected role';
$string['number'] = 'Group/member count';
$string['numgroups'] = 'Number of communities';
$string['nummembers'] = 'Members per community';
$string['manageactions'] = 'Manage';
$string['messagingdisabled'] = 'Successfully disabled messaging in {$a} community(s)';
$string['messagingenabled'] = 'Successfully enabled messaging in {$a} community(s)';
$string['mygroups'] = 'My communities';
$string['othergroups'] = 'Other communities';
$string['overview'] = 'Overview';
$string['participation'] = 'Show community in dropdown menu for activities in community mode';
$string['participation_help'] = 'Should community members be able to select this community for activities in separate or visible communities mode? (Only applicable if community membership is visible or only visible to members.)';
$string['participationshort'] = 'Participation';
$string['potentialmembers'] = 'Potential members: {$a}';
$string['potentialmembs'] = 'Potential members';
$string['printerfriendly'] = 'Printer-friendly display';
$string['privacy:metadata:core_message'] = 'The community conversations';
$string['privacy:metadata:groups'] = 'A record of community membership.';
$string['privacy:metadata:groups:groupid'] = 'The ID of the community.';
$string['privacy:metadata:groups:timeadded'] = 'The timestamp indicating when the user was added to the community.';
$string['privacy:metadata:groups:userid'] = 'The ID of the user which is associated to the community.';
$string['random'] = 'Randomly';
$string['removegroupfromselectedgrouping'] = 'Remove community from grouping';
$string['removefromgroup'] = 'Remove user from community {$a}';
$string['removefromgroupconfirm'] = 'Do you really want to remove user "{$a->user}" from community "{$a->group}"?';
$string['removegroupingsmembers'] = 'Remove all communities from groupings';
$string['removegroupsmembers'] = 'Remove all community members';
$string['removeselectedusers'] = 'Remove selected users';
$string['selectfromgroup'] = 'Select members from community';
$string['selectfromgrouping'] = 'Select members from grouping';
$string['selectfromrole'] = 'Select members with role';
$string['showgroupsingrouping'] = 'Show communities in grouping';
$string['showmembersforgroup'] = 'Show members for community';
$string['toomanygroups'] = 'Insufficient users to populate this number of communities - there are only {$a} users in the selected role.';
$string['usercount'] = 'User count';
$string['usercounttotal'] = 'User count ({$a})';
$string['usergroupmembership'] = 'Selected user\'s membership:';
$string['visibility'] = 'Community membership visibility';
$string['visibility_help'] = '* Visible - all course participants can view who is in the group
* Only visible to members - course participants not in the community can’t view the community or its members
* Only see own membership - a user can see they are in the community but can’t view other community members
* Hidden - only teachers can view the community and its members

Users with the view hidden communities capability can always view community membership.

Note that you can\'t change this setting if the community has members.';
$string['visibilityshort'] = 'Visibility';
$string['visibilityall'] = 'Visible';
$string['visibilitymembers'] = 'Only visible to members';
$string['visibilityown'] = 'Only see own membership';
$string['visibilitynone'] = 'Hidden';
$string['memberofgroup'] = 'Community member of: {$a}';
$string['withselected'] = 'With selected';
