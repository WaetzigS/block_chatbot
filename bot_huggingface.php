<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/classes/query.php'); // Damit die querys abgefragt werden


//Werte aus der Tabelle config holen: get_config funktioniert bei mir nicht.
global $DB ;
$topicid = $_SESSION['topicid'];
$message_request = $_SESSION['message_request'];
$setting_block_chatbot_host = $DB->get_record('config' , ['name' => 'block_chatbot_host']);
$setting_block_chatbot_modell = $DB->get_record('config' , ['name' => 'block_chatbot_trained_modell']);



//Testwiese
$queries = new SQLQuery();
$queries->querys();
//Die Abfrage aus All Queries wird benutzt
$AllQuiz = $queries->querys($userid);



$response = "";
$userInput = $message_request;

// Hugging Face API-Key
$apiKey = $setting_block_chatbot_host->value;

$response = '';
$userInput = $AllQuiz . $message_request;

// HTTP-Request an die Hugging Face API
$url = 'https://api-inference.huggingface.co/models/' . $setting_block_chatbot_modell->value; 

$data = json_encode(['inputs' => $userInput]);

$options = [
    'http' => [
        'header' => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey",
        ],
        'method' => 'POST',
        'content' => $data,
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result !== FALSE) {
    $response = json_decode($result, true);
    $response = $response[0]['generated_text'] ?? 'Keine Antwort erhalten.';
} else {
    $response = 'Fehler bei der Anfrage.';
}
// Jetzt Antwort eintragen (Ohne new stdClass)
$DB->insert_record('chatbot_topicscontent' , [
    'topics' => $topicid,
    'sender' => 0,
    'text' => $response,
    'timecreated' => time()
]);
header("Refresh:0.5");
