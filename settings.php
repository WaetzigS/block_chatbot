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
 * Settings for the HTML block
 *
 * @copyright 2012 Aaron Barnes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   block_chatbot
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configpasswordunmask('block_chatbot_host', get_string('hostchatbot', 'block_chatbot'),
                       get_string('caphostchatbot', 'block_chatbot'), ''));


    // Optionen für das vortrainiere Modell                   
    $options_trainedmodell = [
        'dbmdz/german-gpt2' => 'dbmdz/german-gpt2' ,
    ];

    $settings->add(new admin_setting_configselect('block_chatbot_trained_modell', get_string('trainedmodellchatbot', 'block_chatbot'),
                       get_string('captrainedmodellchatbot', 'block_chatbot'), 'deepset/gbert-base' ,  $options_trainedmodell));

    
}

