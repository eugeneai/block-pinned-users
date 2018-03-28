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
 * Form for editing simple_user_list block instances.
 *
 * @package     block_simple_user_lists
 * @copyright   2018 Evgeny Cherkashin
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_simple_user_list_edit_form extends block_edit_form {


    private function get_users()
    {
        global $DB, $OUTPUT, $PAGE;

        $usernames = [];

        if(empty($this->block->config->users)) return [];
        $ids = $this->block->config->users;

        list($uids, $params) = $DB->get_in_or_equal($ids);
        $rs = $DB->get_recordset_select('user', 'id ' . $uids, $params, '', 'id,firstname,lastname,email');

        foreach ($rs as $record)
        {
            $usernames[$record->id] = fullname($record) . ' ' . $record->email;
        }
        $rs->close();

        return $usernames;
    }

    /**
     * Extends the configuration form for block_simple_user_list.
     */
    protected function specific_definition($mform)
    {

        // Section header title.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Please keep in mind that all elements defined here must start with 'config_'.
        $mform->addElement('text', 'config_title', get_string('simple_user_list_title', 'block_simple_user_list'));
        $mform->setType('config_title', PARAM_TEXT);

        $usernames = $this->get_users();
        $mform->addElement('autocomplete', 'config_users', get_string('simple_user_list_users', 'block_simple_user_list'), $usernames, [
        	'multiple' => true,
            'ajax' => 'tool_lp/form-user-selector',
        ]);
        $mform->addRule('config_users', null, 'required');

    }
}
