<?php

class Menu_m extends Core_db
{  
    public function getStartmenu($lang)
    {
        $menuitems = array(
            array(
                'link' => 'home/index',
                'description' => $lang['login'],
            ),
        );
        return $menuitems;
    }


      public function getUsermenu($lang)
    {
        $menuitems = array(
            array(
                'link' => 'home/index',
                'description' => $lang['dashboard'],
            ),
            array(
                'link' => 'movies/index',
                'description' => $lang['movies'],
            ),
            array(
                'link' => 'series/index',
                'description' => $lang['series'],
            ),
            array(
                'link' => array(
                            array('link' => 'help/windows',
                                   'description' => 'Windows'
                            ),
                            array('link' => 'help/mac',
                                   'description' => 'Mac'
                            ),
                            array('link' => 'help/synology',
                                   'description' => 'Synology'
                            ),
                            array('link' => 'help/kodi',
                                   'description' => 'XBMC/Kodi'
                            ),
                            array('link' => 'help/android',
                                   'description' => 'Android'
                            ),
                            array('link' => 'help/ios',
                                   'description' => 'iOS'
                            ),
                        ),
                'description' => $lang['help'],
            ),
        );
        return $menuitems;
    }

    public function getAdminmenu(){
        $menuitems = array();
        return $menuitems;
    }  
    

}
