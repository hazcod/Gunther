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
            $this->mediamodel->scanmovies($full);
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
            if ($userFull and $this->user->id != $_SESSION['user']){
                $this->user_m->delUser($user);
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
            $formdata = $this->form->getPost();
            if ($formdata and $formdata->username){
                $user = $formdata->username;
                $output = $this->user_m->addUser($user);
                $this->setFlashmessage($user . ' ' . $this->lang['addeduser'] .' <strong>' . $output .'</strong>');
                $this->redirect('admin/index');
            } else {
                $this->setFlashmessage($this->lang['accessdenied'], 'danger');
                $this->redirect('admin/index');
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
