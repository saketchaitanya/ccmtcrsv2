<?php 
namespace common\components\questionnaire;


class QuestionnaireInstructions{

    public static function getContent()
    {

        $content =
        "<h4><strong>Please read these instructions carefully before answering:</strong></h4>
                <ol>
                    <li>Each question passes through the following stages:
                        (The statuses of questionnaire in stage is mentioned.)
                        <ol type='a'>
                            <li>Filling and Saving (status: <b>new</b>)</li>
                            <li>Submitting (status:<b>submitted</b>)</li>
                            <li>Evaluation (status remains submitted)</li>
                            <li>Rework (status: <b>rework</b>) [This happens only when the questionnaire is sent back after evaluation.]</li>
                            <li>Approval (status:<b>approved</b>) </li>
                        </ol>
                    </li>                       
                    <li> You will be able to update the contents only if the questionnaire status is new or rework i.e. you have not submitted it for evaluation first time or after reworking.
                    </li>
                    <li> Each question is supposed to be answered and after answering you have to mark it as <b>'Complete'</b>. If in case there is no information and you intend to skip the question, you should mark the question as <b>'Skipped'</b>. Unless you have updated all the questions in this way, you will not be able to submit the questionnaire. (Please note that the statuses of the questions are different from the questionnaire. The questions can be in statuses:new, complete, skipped or rework.)
                    </li>
                    <li>The questionnaire can be saved anytime and you can add the information by pressing the 'Last Answered Question' button. Alternatively you can use the 'Goto' Button to navigate to your answer straight away.
                    </li>
                    <li>Please provide information only for the month for which the questionnaire is being sent. Dates also should be mentioned accordingly. Dates prior to or after the month of questionnaire will not be accepted by the system.
                    </li>
                    <li>You will only be allowed to send questionnaires from the active year in the system as set by CCMT Team.
                    </li> 
                    <li> You are expected to fill the questionnaire of each month by <b>25th</b> day of the following month.
                    </li>
                </ol>";
        return $content;
    }



}

