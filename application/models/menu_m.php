<?php

class Menu_m extends Core_db
{  
    public function getStartmenu()
    {
        $menuitems = array(
            array(
                'link' => 'home/index',
                'description' => $this->lang['login'],
            ),
        );
        return $menuitems;
    }


      public function getUsermenu()
    {
        $menuitems = array(
            array(
                'link' => 'home/index',
                'description' => $this->lang['dashboard'],
            ),
            array(
                'link' => 'movies/index',
                'description' => $this->lang['movies'],
            ),
            array(
                'link' => 'series/index',
                'description' => $this->lang['series'],
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
                'description' => $this->lang['help'],
            ),
        );
        return $menuitems;
    }

    public function getAdminmenu(){
        $menuitems = array();
        return $menuitems;
    }  
    

}
