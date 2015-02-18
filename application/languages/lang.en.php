<?php

function getLang(){
    //Change this to yes if this is for a language that reads from the right to the left.
    $lang['rtl'] = 'no'; #add bootstrap-rtl.css if you want this

    $lang['title'] = 'Gunther';
    
    $lang['logout']	  = 'Logout';
    $lang['login']        = 'Login';
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

    $lang['movies']       = 'Movies';
    $lang['inactivemov']  = 'Inactive Movies';
    $lang['hiddenmov']    = 'Movies that are already in the library have been hidden.';
    $lang['search']       = 'Search';
    $lang['addmovie']     = 'Add Movie';
    $lang['movieadded']   = 'Movie has been added.';
    $lang['movadderr']    = 'There was an error adding this movie.';
    $lang['searchmovie']  = 'Search for a movie';
    $lang['busymovies']   = 'Inactive Movies';
    $lang['addmovie']     = 'Add Movie';
    $lang['movtitle']     = 'title';
    $lang['nomovies']     = 'No movies found.';

    $lang['nojs']         = 'To view this video, enable JavaScript in your browser or upgrade to one that supports HTML5.';

    $lang['series']       = 'Series';
    $lang['searchshow']   = 'Search for a TV Show';
    $lang['noshows']      = 'No TV Shows found.';
    $lang['addshow']      = 'Add a new TV Show';
    $lang['hiddenshows']  = 'TV Shows that are already in the library have been hidden.';
    $lang['addshow']      = 'Add new TV Show';
    $lang['showadded']    = 'TV Show has been added.';
    $lang['showadderr']   = 'There was an error adding this TV Show.';

    $lang['htwindows']    = 'How-To Windows';
    $lang['htmac']        = 'How-To Mac';
    $lang['htsynology']   = 'How-To Synology';
    $lang['htkodi']       = 'How-To XBMC/Kodi';
    $lang['htandroid']    = 'How-To Android';
    $lang['htios']        = 'How-To iOS';

    $lang['help']         = 'Help';

    $lang['admin']        = 'Administrator';

    return $lang;
}
