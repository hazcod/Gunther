<?php

class Menu_m extends Core_db
{

    public function getMenu($user, $lang){
        if ($user and isset($user->role)){
            return $this->getUsermenu($lang, (strcmp('1', $user->role) == 0));
        } else {
            return $this->getStartmenu($lang);
        }
    }


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


      public function getUsermenu($lang,$admin=false)
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
        if ($admin == true){
            array_push($menuitems, array(
                                'link' => 'admin/index',
                                'description' => '<strong>' . $lang['admin'] . '</strong>',
                        ));
        }
        return $menuitems;
    }

    public function getAdminmenu(){
        $menuitems = array();
        return $menuitems;
    }  
    

}
