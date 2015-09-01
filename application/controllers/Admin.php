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

        $this->t = Load::model('accesslog_m');
        //$this->t->getPendingLogs();
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

    private function tailCustom($filepath, $lines = 1, $adaptive = true) {
        // Open file
        $f = @fopen($filepath, "rb");
        if ($f === false) return false;
        // Sets buffer size
        if (!$adaptive) $buffer = 4096;
        else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
        // Jump to last character
        fseek($f, -1, SEEK_END);
        // Read it and adjust line number if necessary
        // (Otherwise the result would be wrong if file doesn't end with a blank line)
        if (fread($f, 1) != "\n") $lines -= 1;
        // Start reading
        $output = '';
        $chunk = '';
        // While we would like more
        while (ftell($f) > 0 && $lines >= 0) {
        // Figure out how far back we should jump
        $seek = min(ftell($f), $buffer);
        // Do the jump (backwards, relative to where we are)
        fseek($f, -$seek, SEEK_CUR);
        // Read a chunk and prepend it to our output
        $output = ($chunk = fread($f, $seek)) . $output;
        // Jump back to where we started reading
        fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
        // Decrease our line counter
        $lines -= substr_count($chunk, "\n");
        }
        // While we have too many lines
        // (Because of buffer size we might have read too many)
        while ($lines++ < 0) {
        // Find first newline and remove all text before that
        $output = substr($output, strpos($output, "\n") + 1);
        }
        // Close file and return
        fclose($f);
        return trim($output);
    }

    private function generatePassword($length=10) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*()_+=-][{}/><.,/?";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = mt_rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }


    private function getLog($limit=10){
        $result = false;
        if (file_exists($this->settings['LOG'])){
            $result = ltrim($this->tailCustom($this->settings['LOG'], $limit));
        } else {
            error_log('No log file found at ' . $this->settings['LOG']);
            $result = 'No log file found at ' . $this->settings['LOG'];
        }
        return $result;
    }

    public function scanmovies($full=false){
        if ($this->checkAdminAccess()){
            $this->mediamodel->movieProvider()->scanmovies($full);
            $this->setFlashmessage($this->lang['scanstarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function scanshows(){
        if ($this->checkAdminAccess()){
            $this->mediamodel->showProvider()->scanshows();
            $this->setFlashmessage($this->lang['scanstarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function restartmovie(){
        if ($this->checkAdminAccess()){
            $this->mediamodel->showProvider()->restartApp();
            $this->setFlashmessage($this->lang['restarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function restartsshow(){
        if ($this->checkAdminAccess()){
            $this->mediamodel->showProvider()->restartApp();
            $this->setFlashmessage($this->lang['restarted']);
            $this->redirect('admin/index');
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function removeuser($user=false){
        if ($this->checkAdminAccess()){
            $user = $this->user_m->getUserById($user);
            if ($user and $user->id != $this->user->id){
                $this->user_m->delUser($user->id);
                $this->setFlashmessage($this->lang['removeduser']);
                $this->redirect('admin/index');
            } else {
                $this->setFlashmessage($this->lang['delme'], 'danger');
                $this->redirect('admin/index');
            }
        } else {
            $this->setFlashmessage($this->lang['accessdenied'], 'danger');
            $this->redirect('admin/index');
        }
    }

    public function adduser(){
        if ($this->checkAdminAccess()){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $formdata = $this->form->getPost();
                
                $this->form->validateLength('username', 4);
                $this->form->validateLength('name', 4);
                $this->form->validateLength('password', 4);
                $this->form->validateEmail('email');
                $this->form->validateInteger('role');

                if ($formdata && $this->form->isFormValid()){

                    $formdata->username = strtolower($formdata->username);
                    $formdata->email = strtolower($formdata->email);

                    $output = $this->user_m->addUser($formdata->username, $formdata->password, $formdata->name, $formdata->email, $formdata->role);
                    $this->setFlashmessage($formdata->username . ' ' . $this->lang['addeduser'] .' <strong>' . $formdata->password .'</strong>');
                    $this->redirect('admin/index');
                } else {
                    $this->setFlashmessage($this->lang['addusererror'], 'danger');
                    $this->template->formdata = $formdata;
                    $this->template->roles = $this->user_m->getRoles();
                    $this->template->formdata->role = $formdata->role;
                    $this->template->render('admin/adduser');
                }
            } else {
                $this->template->roles = $this->user_m->getRoles();
                $this->template->password = $this->generatePassword();
                $this->template->render('admin/adduser');
            }
        }
    }

    private function recursiveDelete($str) {
        if (is_file($str)) {
            return @unlink($str);
        }
        elseif (is_dir($str)) {
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path) {
                recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }

    public function clearcache($subj=false){
        if ($this->checkAdminAccess()){
            if (!$subj){
                $this->recursiveDelete($this->settings['CACHE_DIR'] . '*');
                $this->mediamodel->fillCache();
                $this->setFlashmessage($this->lang['flushed']);
                $this->redirect('admin/index');
            } elseif ($subj == 'movies'){
                $this->mediamodel->flushMovieCache();
                $this->setFlashmessage($this->lang['moviesflushed']);
                $this->redirect('admin/index');
            } elseif ($subj == 'shows'){
                $this->mediamodel->flushShowCache();
                $this->setFlashmessage($this->lang['showsflushed']);
                $this->redirect('admin/index');
            } else {
                $this->setFlashmessage('Invalid URL', 'danger');
                $this->redirect('admin/index');
            }
        }
    }

    public function clearlog(){
        if ($this->checkAdminAccess()){
            $myTextFileHandler = @fopen($this->settings['LOG'], "r+");
            if ($myTextFileHandler){
                ftruncate($myTextFileHandler, 0);
                fclose($myTextFileHandler);
                $this->setFlashmessage($this->lang['logcleared']);
            } else {
                $this->setFlashmessage($this->lang['lognotcleared'] . ' ' . $this->settings['LOG'], 'danger');
            }
            $this->redirect('admin/index');
        }
    }

    public function index()
    {
        if ($this->checkAdminAccess()){
            $this->template->users = $this->user_m->getUsers();
            $this->template->log = $this->getLog($this->settings['LAST_LOG']);
            $this->template->render('admin/index');
        }
    }


}
