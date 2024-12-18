<?php

require_once(dirname(__FILE__).'/../../config.php');
global  $USER , $DB ;
$userid = $USER->id;
$countactivetopic = $DB->count_records('chatbot_topics' , ['user' => $userid , 'active' => 1]) ;
$currentchatcontent = [] ;

if ($countactivetopic > 0) {
    $currentchatcontent = $DB->get_records_sql(
        'SELECT 
            content.text as chatcontent ,
            case when content.sender = 0 then "#a3e5f4" else "#d3f4a3" end as bgcolor,
            case when content.sender = 0 then "right" else "left" end as align
        from 
            {chatbot_topicscontent} content
            join {chatbot_topics} topics on topics.id = content.topics 
        where
            topics.active = 1 and
            topics.user = :userid
        order by content.id asc'
         , 
            [
            'userid' => $userid ,
            ]
        );
    }

// Setze den Content-Type und gib die Daten als JSON zurück
header('Content-Type: application/json');
echo json_encode($currentchatcontent);
