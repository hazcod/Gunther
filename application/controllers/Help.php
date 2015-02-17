<?php

class Help extends Core_controller
{

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

    public function index()
    {
        $this->redirect('dashboard/index');
    }

    public function windows(){
        $this->template->setPagetitle('How-To Windows - Gunther');
        $this->template->render('help/windows');
    }

    public function mac(){
        $this->template->setPagetitle('How-To Mac - Gunther');
        $this->template->render('help/mac');
    }

    public function synology(){
        $this->template->setPagetitle('How-To Synology - Gunther');
        $this->template->render('help/synology');
    }

    public function android(){
        $this->template->setPagetitle('How-To Android - Gunther');
        $this->template->render('help/android');
    }

    public function ios(){
        $this->template->setPagetitle('How-To iOS - Gunther');
        $this->template->render('help/ios');
    }


}
