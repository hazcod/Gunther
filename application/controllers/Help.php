<?php

class Help extends Core_controller
{

    public function __construct()
    {
        parent::__construct(true);
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');
        //load models
        $this->menu_m = Load::model('menu_m');
        $this->langs_m = Load::model('langs_m');
        $this->user_m = Load::model('user_m');

        $this->template->menuitems = $this->menu_m->getUserMenu($this->lang);
        $this->template->langs = $this->langs_m->getLangs();	
    }

    public function index()
    {
        $this->redirect('dashboard/index');
    }

    public function windows(){
        $this->template->setPagetitle($this->lang['htwindows'] . ' - ' . $this->lang['title']);
        $this->template->render('help/windows');
    }

    public function mac(){
        $this->template->setPagetitle($this->lang['htmac'] . ' - ' . $this->lang['title']);
        $this->template->render('help/mac');
    }

    public function synology(){
        $this->template->setPagetitle($this->lang['htsynology'] . ' - ' . $this->lang['title']);
        $this->template->render('help/synology');
    }

    public function kodi(){
        $this->template->setPagetitle($this->lang['htkodi'] . ' - ' . $this->lang['title']);
        $this->template->render('help/kodi');
    }

    public function android(){
        $this->template->setPagetitle($this->lang['htandroid'] . ' - ' . $this->lang['title']);
        $this->template->render('help/android');
    }

    public function ios(){
        $this->template->setPagetitle($this->lang['htios'] . ' - ' . $this->lang['title']);
        $this->template->render('help/ios');
    }


}
