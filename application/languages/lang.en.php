<?php

function getLang($controller){
    //Change this to yes if this is for a language that reads from the right to the left.
    $lang['rtl'] = 'no'; #add bootstrap-rtl.css if you want this

    $lang['title'] = 'Gunther';
    
    $lang['wronglogin']   = 'Your username & password combination is invalid.';
    $lang['invalidlogin'] = 'Your username must be at least 4 characters.';
    $lang['loggedout']    = 'You have been successfully logged out.';
    $lang['accessdenied'] = 'You do not have access to this page.';
    $lang['loginto']      = 'Login to continue.';
    $lang['username']     = 'Username';
    $lang['email']        = 'Email address';
    $lang['username']     = 'Username';
    $lang['password']     = 'Password';
    $lang['signin']       = 'Sign in';

    $lang['dashboard']    = 'Dashboard';
    $lang['recentmovies'] = 'Recently added Movies';
    $lang['recentepi']    = 'Recently added TV Episodes';

    return $lang;
}
