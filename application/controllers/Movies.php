<?php

class Movies extends Core_controller
{

    public function __construct()
    {
        parent::__construct('movies');
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        //load mcrypt_module_self_test(algorithm)
        $this->menu_m = Load::model('menu_m');
        $this->langs_m = Load::model('langs_m');
        $this->user_m = Load::model('user_m');

        $this->template->menuitems = $this->menu_m->getUserMenu();
        $this->template->langs = $this->langs_m->getLangs();
        //set page title
        $this->template->setPagetitle($this->lang['movies'] . ' - ' . $this->lang['']);  

        global $settings;
        $this->settings = $settings;
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

    public function getAllMovies(){
        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list'))->movies;
    }

    public function getDoneMovies(){
        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?status=done'))->movies;
    }


    public function getBusyMovies(){
        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?status=active'))->movies;
    }

    public function busy(){
        if ($this->checkPrivilege() == true) {
         $this->template->setPagetitle($this->lang['inactivemov'] . ' - ' . $this->lang['title']);
            $this->template->movies = $this->getBusyMovies();
            $this->template->render('media/movies.busy');
        } 
    }

    public function findExistingMovie($title){
        return json_decode(file_get_contents($this->settings['CP_API'] . 'media.list?search=' . urlencode($title)))->movies;
    }

    public function findMovies($title){
        return json_decode(file_get_contents($this->settings['CP_API'] . 'movie.add?title=' . urlencode($title)));
    }

    public function getMediaInfo($title){
        $url = "http://www.omdbapi.com/?type=movie&s=" . urlencode($title);
        return json_decode(file_get_contents($url))->Search;
    }

    public function addMovie($id){
        return json_decode(file_get_contents($this->settings['CP_API'] . 'movie.add?identifier=' . urlencode($id)))->success;
    }

    private function isMovieInList($list, $id){
        $result = false;
        foreach ($list as $movie){
            if (strcmp($movie->info->imdb,$id) == 0){
                $result = true;
            }
        }
        return $result;
    }

     public function search(){
        if ($this->checkPrivilege() == true) {
            $formdata = $this->form->getPost();
            $this->template->searchterm = $formdata->title;
            $existing = $this->getAllMovies();
            $arr=array();
            foreach ($this->getMediaInfo($formdata->title) as $result){
                $id = $result->imdbID;
                if ($this->isMovieInList($existing, $id) == false){
                    array_push($arr, $result);
                } else {
                    $this->setCurrentflashmessage($this->lang['hiddenmov'], 'info');
                }
            }
            $this->template->results = $arr;
            $this->template->setPagetitle($this->lang['search'] . ': ' . $formdata->title . ' - Gunther');
            $this->template->render('media/movies.add');
        }
    }

    public function add($id=false){
        if ($this->checkPrivilege() == true) {
            $this->template->setPagetitle($this->lang['addmovie'] . ' - ' . $this->lang['title']);
            if ($id){
                if ($this->addMovie($id) == true){
                    $this->setflashmessage($this->lang['movieadded'], 'info');
                } else {
                    $this->setflashmessage($this->lang['movadderr'], 'danger');
                }
                $this->redirect('movies/index');
             } else {
                $this->template->render('media/movies.add');
             }
        }
     }

    public function index(){
        if ($this->checkPrivilege() == true) {
            if ($_POST){
                $formdata = $this->form->getPost();
                $this->template->searchterm = $formdata->search;
                $this->template->movies = $this->findExistingMovie($formdata->search);
            } else {
                $this->template->movies = $this->getDoneMovies();
            }
            $this->template->render('media/movies');
        } 
    }


}