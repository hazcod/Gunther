<?php

class Info extends Core_controller
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

    }

    public function humanFileSize($size, $format='')
    {
	if ( (!$unit && $size >= 1<<30) || $unit == "GB")
		return number_format($size/(1<<30),2). " GB";
	if ( (!$unit && $size >= 1<<20) || $unit == "MB")
		return number_format($size/(1<<20),2) . " MB";
	if ( (!$unit && $size >= 1<<10) || $unit == "KB")
		return number_format($size/(1<<10),2) . " KB";
	return number_format($size) . " bytes";
    }

    public function movie($id){
        $this->template->info = $this->mediamodel->getMovie($id);
        if ($this->template->info){
	    $this->template->info->release = $this->mediamodel->getRelease($this->template->info, true);
            $this->template->info->release->size = $this->humanFileSize(filesize($this->template->info->release->files->movie[0]));
	    $this->template->id = $id;
            $this->template->setPagetitle($this->template->info->info->original_title . ' - ' . $this->lang['title']);  
            $this->template->render('info/movie');
        } else {
            $this->setFlashmessage($this->lang['movienotfound'], 'danger');
            $this->redirect('movies/index');
        }
    }

    public function episode($id){
        
    }

    public function index(){
        $this->redirect('dashboard/index');
    }


}
