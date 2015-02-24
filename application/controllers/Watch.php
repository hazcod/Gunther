<?php

class Watch extends Core_controller
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
        $this->template->setPagetitle($this->lang['title']);	
    }

    private function streamMovie($id){
        $movie = $this->mediamodel->getMovie($id); 
        $stream = new VideoStream($movie->releases[0]->files->movie[0]);
        return $stream->start();
    }

    private function streamShow($id){
        $parts = explode('-', $id);
        $serie_id = $parts[0];
        $season_id = $parts[1];
        $episode_id = $parts[2];
        $serie = $this->mediamodel->getShow($serie_id, $season_id, $episode_id);
        $stream = new VideoStream($serie->location);
        return $stream->start();
    }

   public function stream($id=false){
        include __DIR__ . '/../includes/VideoStream.php';

        $prefix = substr($id, 0, 2);
        if (strcmp($prefix, 'ss') == 0){
            #custom id, so tv show
            return $this->streamShow(substr($id, 2));
        } else {
            if (strcmp($prefix, 'tt') == 0){
                $id = substr($id, 2);
            }
            return $this->streamMovie($id);
        }
   }

   private function getMovieSub($id, $lang){
        $movie = $this->mediamodel->getMovie($id);
        $subfile = false;
        foreach ($movie->releases[0]->files->subtitle as $sub){
            if ($lang == substr($sub, strpos(substr($sub,0,-4), '.')+1, -4)){
               $subfile = $sub;
            }
        }
        return $subfile;
   }

   private function getShowSub($id, $lang){
        $parts = explode('-', $id);
        $serie_id = $parts[0];
        $season_id = $parts[1];
        $episode_id = $parts[2];
        $episode = $this->mediamodel->getShow($serie_id, $season_id, $episode_id);
        #TODO; get subs

        $subfile = false;
        return $subfile; 
   }

   public function sub($id=false, $lang=false){
        $prefix = substr($id, 0, 2);
        if (strcmp($prefix, 'ss') == 0){
            #custom id, so tv show
            $subfile = $this->getShowSub($id, $lang);
        } else {
            if (strcmp($prefix, 'tt') == 0){
                $id = substr($id, 2);
            }
            $subfile = $this->getMovieSub($id, $lang);
        }
        return $this->offerFile($subfile);
   }

   private function offerFile($file){
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Type: text/plain'); # Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
        header('Content-Length: ' . strlen($file));
        header('Connection: close');
        return file_get_contents($file);
   }


   private function watchMovie($id){
        $movie = $this->mediamodel->getMovie($id);
        $filepath = $movie->releases[0]->files->movie[0];
        $this->template->file = $id;
        $this->template->type = $this->mediamodel->getMimeType($filepath);
        $this->template->codec= $this->mediamodel->getCodecInfo($filepath)['videoCodec'];
        $subs = array();
        foreach ($movie->releases[0]->files->subtitle as $sub){
            array_push($subs, array(
                    'file' => $sub,
                    'lang' => substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                    'label'=> substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                ));
        }
        $this->template->streamstr = $id;
        $this->template->subs = $subs;
        $this->template->setPagetitle($movie->info->original_title . ' - Gunther');
        $this->template->render('media/watch');
   }

   private function watchShow($id){
        $parts = explode('-', $id);
        $serie_id = $parts[0];
        $season_id = $parts[1];
        $episode_id = $parts[2];
        $episode = $this->mediamodel->getEpisode($serie_id, $season_id, $episode_id);
        $this->template->file = $episode->location;
        $this->template->type = $this->mediamodel->getMimeType($episode->location);
        $this->template->codec = $this->mediamodel->getCodecInfo($episode->location)['videoCodec'] . ',' . $this->mediamodel->getCodecInfo($episode->location)['audioCodec'];
        $this->template->streamstr = 'ss' . $id;
        $subs = array();
        foreach (glob(basename($episode->location . '*.srt')) as $sub){
            array_push($subs, array(
                    'file' => $sub,
                    'lang' => substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                    'label'=> substr($sub, strpos(substr($sub,0,-4), '.')+1, -4),
                ));
        }
        $this->template->subs = $subs;
        $this->template->setPagetitle($episode->name . ' - ' . $this->lang['title']);
        $this->template->render('media/watch');
   }

    public function index($id=false)
    {
        $prefix = substr($id, 0, 2);
        if (strcmp($prefix, 'ss') == 0){
            #custom id, so tv show
            $this->watchShow(substr($id, 2));
        } else {
            if (strcmp($prefix, 'tt') == 0){
                $id = substr($id, 2);
            }
            $this->watchMovie($id);
        }
    }


}
