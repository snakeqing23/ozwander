<?php

function qa_send_notification($userid, $email, $handle, $subject, $body, $subs, $html = false) 
{ 
    if ( $subject == qa_lang('emails/welcome_subject') ) 
        return qa_send_notification_base($userid, $email, $handle, $subject, $body, $subs, $html); 
    else 
        return true; 
}
