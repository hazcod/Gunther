<?php

class Home extends Core_controller
{
    public function __construct()
    {
        parent::__construct();
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');

        //set page title
        $this->template->setPagetitle($this->lang['title']);	
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
        $formdata = $this->form->getPost();
        $this->form->validateLength('username', 4);
        $this->form->validateLength('password', 5);

        if ($this->form->isFormValid()) {
            if ($this->user_m->isValid(strtolower($formdata->username), $formdata->password)){
                $this->user = $this->user_m->getUserByLogin(strtolower($formdata->username));
		$_SESSION['user'] = $this->user->id;
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
