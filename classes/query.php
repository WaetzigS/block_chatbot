<?php
require_once(dirname(__FILE__).'/../../../config.php');
defined('MOODLE_INTERNAL') || die();

// Alle Tests des Users:

class SQLQuery {
    public function querys() {
        global  $USER , $DB ;
        $userid = $USER->id;
        $AllQuiz = $DB->get_records_sql(
            'SELECT 
                quiz.course as course , 
                quiz.name as quiz,
                round((attempt.sumgrades  / quiz.grade) * 100 , 2)  as result ,
                attempt.timefinish as datefinish
            from 
                {quiz_attempts} attempt
                join {quiz} quiz on quiz.id = attempt.quiz 
            where
                attempt.userid = :userid and
                attempt.timefinish IS NOT NULL and
                attempt.timefinish <> 0', 
            );
            return $DB->get_records_sql($AllQuiz, ['userid' => $userid]);
            
        

        $AllGroups = $DB->get_records_sql(
            'SELECT
                groups.name as groupname
            FROM
                {groups_members} groupmembers,
                join {groups} groups on groupmembers.groupid = groups.id
            WHERE
                groupmembers.userid = :userid
            ' ,
            [
                'userid' => $userid ,
            ]
        );
    }
}