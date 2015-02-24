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

    private function checkAdminAccess(){
        if ($this->isAdminUser() == false){
            $this->setFlashmessage($this->lang['accessdenied'],'danger');
            $this->redirect('dashboard/index');
            return false;
        } else {
            return true;
        }
    }

/*    private function getUsers(){
        $result = array();

        $handle = fopen($this->settings['AUTH_DIGEST_FILE'], "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $result[] = explode(':', $line)[0];                
            }
            fclose($handle);
        } else {
            error_log('Could not open digest file at ' . $this->settings['AUTH_DIGEST_FILE']);
        }
        return $result;
    }*/

    public function scanmovies(){
        if ($this->checkAdminAccess()){
            $this->mediamodel->scanmovies();
            $this->setFlashmessage($this->lang['scanstarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function scanshows(){
        if ($this->checkAdminAccess()){
            $this->mediamodel->scanshows();
            $this->setFlashmessage($this->lang['scanstarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function restartcp(){
        if ($this->checkAdminAccess()){
            $this->mediamodel->restartCouch();
            $this->setFlashmessage($this->lang['restarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function restartsick(){
        if ($this->checkAdminAccess()){
            $this->mediamodel->restartSick();
            $this->setFlashmessage($this->lang['restarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function removeuser($user=false){
        if ($this->checkAdminAccess()){
            $userFull = $this->user_m->getUserById($user);
            if ($userFull and strcmp($userFull->role, 'admin') != 0){
                $this->user_m->delUser($user);
                shell_exec('scripts/delUser.sh ' . $this->settings['AUTH_DIGEST_FILE'] . ' ' . $userFull->login);
                $this->setFlashmessage($this->lang['removeduser']);
                $this->redirect('admin/index');
            } else {
                $this->setFlashmessage($this->lang['deladmin'], 'danger');
                $this->redirect('admin/index');
            }
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function adduser(){
        if ($this->checkAdminAccess()){
            $formdata = $this->form->getPost();
            if ($formdata and $formdata->username){
                $user = $formdata->username;
                $output = shell_exec('scripts/addUser.sh ' . $this->settings['AUTH_DIGEST_FILE'] . ' ' . $user);
                $output = str_replace("\n", "", $output);
                $this->user_m->addUser($user, $output);
                $this->setFlashmessage($user . ' ' . $this->lang['addeduser'] .' <strong>' . $output .'</strong>');
                $this->redirect('admin/index');
            } else {
                $this->setFlashmessage($this->lang['accessdenied'], 'danger');
                $this->redirect('admin/index');
            }
        }
    }

    public function index()
    {
        if ($this->checkAdminAccess()){
            $this->template->users = $this->user_m->getUsers();
            $this->template->render('admin/index');
        }
    }


}
