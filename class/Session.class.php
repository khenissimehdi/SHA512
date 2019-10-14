<?php 

class Session
{

    public function start()
    {

    
        if( session_status() == PHP_SESSION_NONE )
        {
            if(headers_sent() == false)
            {
                session_start();
            }
            else
            {
                throw SessionException("HTTP HEADER ALREADY BEEN SENT !");

            }
           
           
        }
        elseif(session_status() != PHP_SESSION_ACTIVE)
        {
            throw SessionException("session activation failed");

        }


        //session_start();
    }

}