<?php

class Admin extends Core_controller
{
    public function __construct()
    {
        parent::__construct(true);
        //set our partials in the template
        $this->template->setPartial('navbar')
            ->setPartial('headermeta')
            ->setPartial('footer')
            ->setPartial('flashmessage');

        //set page title
        $this->template->setPagetitle($this->lang['admin'] . ' - ' . $this->lang['title']);	
    }

    public function checkAdminAccess(){
        if ($this->isAdminUser() = false){
            $this->setFlashmessage($this->lang['accessdenied'],'danger');
            $this->redirect('dashboard/index');
        }
    }

    public function index()
    {
        if ($this->checkAdminAccess()){
            $this->render('admin/index');
        }
    }


}
