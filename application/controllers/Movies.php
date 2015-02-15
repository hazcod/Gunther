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

    public function getAllMovies(){
        return json_decode(file_get_contents($this->api . 'media.list'))->movies;
    }

    public function getDoneMovies(){
        return json_decode(file_get_contents($this->api . 'media.list?status=done'))->movies;
    }


    public function getBusyMovies(){
        return json_decode(file_get_contents($this->api . 'media.list?status=active'))->movies;
    }

    public function busy(){
        if ($this->checkPrivilege() == true) {
		 $this->template->setPagetitle('Inactive Movies - Gunther');
            $this->template->movies = $this->getBusyMovies();
            $this->template->render('media/movies.busy');
        } 
    }

	public function findExistingMovie($title){
        return json_decode(file_get_contents($this->api . 'media.list?search=' . $title))->movies;
	}

	public function findMovies($title){
		return json_decode(false);
	}

     public function search(){
		if ($this->checkPrivilege() == true) {
			$formdata = $this->form->getPost();
			$this->template->searchterm = $formdata->title;
			$existing = $this->findExistingMovie($formdata->title);
			$arr=array();
			for ($this->findMovies($formdata->title) as $result){
				//TODO: if $result is not in $existing, append to $arr
			}
			$this->template->results = $arr;
			$this->template->setPagetitle('Search: ' . $formdata->title);
			$this->template->render('media/movies.add');
		}
	}

	public function add(){
        if ($this->checkPrivilege() == true) {
			$this->template->setPagetitle('Add movie - Gunther');
		 	if ($_POST){

			 } else {
	            $this->template->render('media/movies.add');
			 }
        }
     }

    public function index()
    {
        if ($this->checkPrivilege() == true) {
            $this->template->movies = $this->getDoneMovies();
            $this->template->render('media/movies');
        } 
    }


}
