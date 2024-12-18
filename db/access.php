<?php
// Sicherstellen, dass die Datei innerhalb von Moodle aufgerufen wird
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'block/chatbot:myaddinstance' => array(
        'captype' => 'write', // Berechtigungstyp
        'contextlevel' => CONTEXT_SYSTEM, // Gültig auf der Systemeebene
        'requiredby' => array('block/chatbot'), // Benötigt von diesem Block
    ),
    // Weitere Berechtigungen hier...
);
