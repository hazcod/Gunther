<?php

class Home extends Core_controller
{
    public function __construct()
    {
        parent::__construct('start');
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        //load models
        $this->menu_m = Load::model('menu_m');
        $this->langs_m = Load::model('langs_m');
	   $this->user_m = Load::model('user_m');

        $this->template->menuitems = $this->menu_m->getStartMenu();
        $this->template->langs = $this->langs_m->getLangs();
        //set page title
        $this->template->setPagetitle('Gunther');	
    }

    public function index()
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('dashboard');
        } else {
            $this->template->render('home/login');
        }
    }

    public function login()
    {
        $formdata = $this->form->getPost();//get post data
        //Make sure data isn't empty
        $this->form->validateLength('username', 4);
        //$this->form->validateLength('password', 6);
        //if everything is validated..
        if ($this->form->isFormValid()) {
            $pass = sha1('pumpkin' . $formdata->password .  'spice');
		 if ($this->user_m->isValid(strtolower($formdata->username), $pass)){
			$_SESSION['user'] = $formdata->username;
			$this->redirect('dashboard');
		 } else {
            $this->template->formdata = $formdata;
            $this->setCurrentFlashmessage($this->lang['wronglogin'], 'danger');
            $this->template->render('home/login');
		}
        } else {
            $this->template->formdata = $formdata;
            $this->setCurrentFlashmessage($this->lang['invalidlogin'], 'danger');
            $this->template->render('home/login');
        }
    }

    public function logout()
    {
        session_unset();
        $this->setFlashmessage($this->lang['loggedout']);
        $this->redirect("home");
    }


}
