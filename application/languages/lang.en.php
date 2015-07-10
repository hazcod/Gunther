<?php

function getLang(){
    //Change this to yes if this is for a language that reads from the right to the left.
    $lang['rtl'] = 'no'; #add bootstrap-rtl.css if you want this

    $lang['title'] = 'Gunther';

    $lang['status'] = 'Status';
    
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
    $lang['movienotfound']= 'Movie could not be found.';
    $lang['actors']       = 'Actors';

    $lang['nojs']         = 'To view this video, enable JavaScript in your browser or upgrade to one that supports HTML5.';

    $lang['series']       = 'Series';
    $lang['season']       = 'Season';
    $lang['seasons']      = 'Seasons';
    $lang['searchshow']   = 'Search for a TV Show';
    $lang['noshows']      = 'No TV Shows found.';
    $lang['addshow']      = 'Add a new TV Show';
    $lang['hiddenshows']  = 'TV Shows that are already in the library have been hidden.';
    $lang['addshow']      = 'Add new TV Show';
    $lang['showadded']    = 'TV Show has been added.';
    $lang['showadderr']   = 'There was an error adding this TV Show.';
    $lang['noseasons']    = 'There are no seasons available for this show.';
    $lang['shownotfound'] = 'Episode could not be found.';

    $lang['htwindows']    = 'How-To Windows';
    $lang['htmac']        = 'How-To Mac';
    $lang['htsynology']   = 'How-To Synology';
    $lang['htkodi']       = 'How-To XBMC/Kodi';
    $lang['htandroid']    = 'How-To Android';
    $lang['htios']        = 'How-To iOS';

    $lang['help']         = 'Help';

    $lang['admin']        = 'Administrator';
    $lang['users']        = 'Users';
    $lang['add']          = 'Add';
    $lang['deluserc']     = 'Are you sure you want to remove this user?';
    $lang['addeduser']    = 'has been added with password ';
    $lang['deladmin']     = 'You cannot remove an administrator.';
    $lang['removeduser']  = 'User has been removed. (webdav + panel access)';
    $lang['refreshlib']   = 'Refresh library (fast)';
    $lang['refreshlibfull']='Re-scan library (slow)' ;
    $lang['restart']      = 'Restart';
    $lang['scanstarted']  = 'A scan of your library has been initiated. This can take a while..';
    $lang['restarted']    = 'A restart of the service has been initiated. Functionality may be unavailable for a short period..';
    $lang['clearcaches']  = 'Clear all caches';
    $lang['flushmovies']  = 'Flush movie cache';
    $lang['flushshows']   = 'Flush TV Show cache';
    $lang['flushed']      = 'All caches have been forcefully cleared.';
    $lang['moviesflushed']= 'Movie information cache has been cleared.';
    $lang['showsflushed'] = 'TV Show information cache has been cleared.';
    $lang['logcleared']   = 'Logfile has been cleared.';
    $lang['clearlog']     = 'Clear log';
    $lang['lognotcleared']= 'Log could not be cleared. Permissions problem? ';

    $lang['readmore']     = 'Read more';
    $lang['synology-info']= '...offers proven NAS devices for home and enterprise users. An easy web interface smoothens the experience, while the community packages give you loads of functionality.';
    $lang['synology-howto']= 'How to connect your Synology device to the Media collection';
    

    return $lang;
}
