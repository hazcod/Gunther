<?php

function getLang($controller){
    //Change this to yes if this is for a language that reads from the right to the left.
    $lang['rtl'] = 'no';
    switch ($controller) {  
       //login page  
      case 'start':
        $lang['wronglogin']      = 'Your username & password combination is invalid.'; break;
        $lang['invalidlogin']      = 'Your username must be at least 4 characters.'; break;
        $lang['loggedout']       = 'You have been successfully logged out.'; break;
        break;

      case 'dashboard':
       $lang['accessdenied'] = 'You do not have access to this page.'; break;
       #$lang['loggedout'] = 'You have been succcessfully logged out.'; break;


      case 'admin':
        $lang['accessdenied'] = 'You do not have access to this page.'; break;
      
      default: error_log('Lang info not found for controller ' . $controller);

    }
    return $lang;
}
