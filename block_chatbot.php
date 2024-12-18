<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot . '/blocks/chatbot/classes/chatbot.php');

defined('MOODLE_INTERNAL') || die();




class block_chatbot extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_chatbot');
    }

    function instance_allow_multiple() {
        return true;
    }

    function has_config() {
        return true;
    }

    // Inhalt erstellen
    public function get_content() {
        global $OUTPUT , $USER , $DB ;
        //Die Form anzeigen
        $mform = new request_message();
        $message_request = '';
        $userid = $USER->id;
        $countactivetopic = $DB->count_records('chatbot_topics' , ['user' => $userid , 'active' => 1]) ;
        $currentchatcontent = [] ;
        $response_bot = '';
   
        //Hier wird jetzt die Anfrage nsch Klick auf Send bearbeitet:
        if ($mform->is_cancelled()) { // eigentlich haben wir keinen Cancel Button. Es ist Standard code
            //redirect($CFG->wwwroot . '/index.php/' , 'Zurück zum Dashboard'); // Falls es wäre, dann kann redirected werden
            //Updatbefehl, dass den aktuellen Chat active auf 0 setzt
            if ($countactivetopic > 0) {
                //currenttopic id herausfinden
                $currenttopicid = $DB->get_record('chatbot_topics' , ['user' => $userid , 'active' => 1]) ;
                $record = new stdClass();
                $record->id = $currenttopicid->id;
                $record->active = 0;
                if ($DB->update_record('chatbot_topics' , $record)) {
                    \core\notification::add(get_string('delete_topic', 'block_chatbot'), \core\output\notification::NOTIFY_SUCCESS);
                }

            }
            
        } else if ($data = $mform->get_data()) {
            $message_request = $data->request_message; //Anfrage aus der Form holen
            //Prüfen, wenn noch kein Topic vorhanden ist, dann erstelle ein neue Topic mit dem User;
            //Wenn 0, dann lege neues Topic an, sonst führe alten Chat fort
            if ($countactivetopic == 0) {
                $insertnewtopic = new stdClass();
                $insertnewtopic->user = $userid;
                $insertnewtopic->name = $message_request;
                $insertnewtopic->timecreated = time();
                $insertnewtopic->active = 1;
                $DB->insert_record('chatbot_topics' ,  $insertnewtopic);
                
                //Insert die Anfrage in die Chatbotcontent
                // Erstmal die Topicontent-ID bekommen
                $topic = $DB->get_record('chatbot_topics' , ['user' => $userid , 'active' => 1]);
                $topicid = $topic->id;

                // Jetzt Einträge vorbereiten
                $insertnewtopiccontent = new stdClass();
                $insertnewtopiccontent->topics = $topicid;
                $insertnewtopiccontent->sender = $userid;
                $insertnewtopiccontent->text = $message_request;
                $insertnewtopiccontent->timecreated = time();
                $DB->insert_record('chatbot_topicscontent' , $insertnewtopiccontent);

                // Jetzt Script aus der bot_own durchlaufen lassen. Den Wert respnse dann hier ausgeben
                $_SESSION['topicid'] = $topicid;
                $_SESSION['message_request'] = $message_request;
                include 'bot_huggingface.php';                   
            } 

        } 


        // Wurde jetzt heruntergezogen.
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

        // Alles, was in das Template(Mustache) gehört
        $templatecontext = (object)[
            'intro_text' => get_string('intro_text', 'block_chatbot') ,      
            'currentchat' => array_values($currentchatcontent) ,
            'currentchattext' => get_string('yourcurrentchatheadline', 'block_chatbot') ,
            'currentchatanswer' => $response_bot ,
            'request_message_inputform' => $mform->render() ,
        ];
        
        if ($this->content !== null) {
            return $this->content;
        } else
        {
            $this->content = new stdClass;
            //Hier der Inhalt
            $this->content->text =  $OUTPUT->render_from_template('block_chatbot/chatbot', $templatecontext);    
            //Hier der footer
            //$this->content->footer = 'Abschluss';
            return $this->content;
        }
    }

}