<?php
require_once(dirname(__FILE__).'/../../config.php');


//Werte aus der Tabelle config holen: get_config funktioniert bei mir nicht.
global $DB ;
$topicid = $_SESSION['topicid'];
$message_request = $_SESSION['message_request'];

$response = "";
$setting_block_chatbot_host = $DB->get_record('config' , ['name' => 'block_chatbot_host']);
$setting_block_chatbot_port = $DB->get_record('config' , ['name' => 'block_chatbot_port']);
$serverUrl = $setting_block_chatbot_host->value . ':' . $setting_block_chatbot_port->value ; // URL des Flask-Servers
$userInput = $message_request;

$ch = curl_init($serverUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $userInput]));

$apiResponse = curl_exec($ch);

if (curl_errno($ch)) {
    $response = 'Fehler: ' . curl_error($ch);
} else {
    $decodedResponse = json_decode($apiResponse, true);
    $response = $decodedResponse['response'] ?? 'Tut mir leid, ich habe nichts verstanden.';
}

// Jetzt Antwort eintragen (Ohne new stdClass)
$DB->insert_record('chatbot_topicscontent' , [
    'topics' => $topicid,
    'sender' => 0,
    'text' => $response,
    'timecreated' => time()
]);



curl_close($ch);
