<?php

defined('MOODLE_INTERNAL') || die();

// Die Forms erstellen https://docs.moodle.org/dev/Form_API
require_once("$CFG->libdir/formslib.php");

class request_message extends moodleform {
    // Add elements to form.
    public function definition() {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form; // Don't forget the underscore!

        // Add elements to your form.
        $mform->addElement('textarea', 'request_message', get_string('userrequest' , 'block_chatbot'));
        // Set type of element.
        $mform->setType('request_message', PARAM_NOTAGS);
        // Default value.
        $mform->setDefault('request_message', get_string('userrequestdefault' , 'block_chatbot'));

        $buttons = [ 
            [
                'click' => 'window.alert("Test");'
            ]

        ];

        //$mform->addElement('submit', 'request_send', get_string('userrequestbutton', 'block_chatbot'));
        $this->add_action_buttons(true, get_string('userrequestbutton', 'block_chatbot') , $buttons);
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}
