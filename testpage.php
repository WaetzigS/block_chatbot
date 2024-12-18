<?php

//defined('MOODLE_INTERNAL') || die();

// So müsste eine normaler Seite aussehen
require_once(dirname(__FILE__).'/../../config.php');

// COntext setzen --> In diesem Fall system --> https://docs.moodle.org/405/en/Context
$PAGE->set_context(\context_system::instance());
//URL definieren
$PAGE->set_url(new moodle_url('/blocks/chatbot/testpage.php'));
// Titel definieren
$PAGE->set_title('Das ist eine Testseite');


// Moodler Core Output Variable 

echo $OUTPUT->header();

$templatecontext = (object)[
    'texttodisplay' => get_string('pluginname', 'block_chatbot') ,
];

echo $OUTPUT->render_from_template('block_chatbot/testpage', $templatecontext);

echo $OUTPUT->footer();