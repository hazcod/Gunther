<?php

class Movies extends Core_controller
{
   var $api = "http://localhost:5050/api/40389981c6a54cb4a3b813a4961e249d/";

    public function __construct()
    {
        parent::__construct('movies');
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        //load models
        $this->menu_m = Load::model('menu_m');
        $this->langs_m = Load::model('langs_m');
        $this->user_m = Load::model('user_m');

        $this->template->menuitems = $this->menu_m->getUserMenu();
        $this->template->langs = $this->langs_m->getLangs();
        //set page title
        $this->template->setPagetitle('Movies - Gunther');	
    }

    function checkPrivilege()
    {
        if (!isset($_SESSION['user'])){
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('home/index');
            return false;
        } else {
            return true;
        }
    }

    public function getMovies(){
        return json_decode(file_get_contents($this->api . 'media.list'));
    }

    public function index()
    {
        if ($this->checkPrivilege() == true) {
            $this->template->movies = $this->getMovies();
            $this->template->render('media/movies');
        } 
    }


}
