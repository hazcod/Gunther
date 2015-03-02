<?php
class Langs_m
{
    var LANG_PATH = APPLICATION_PATH . 'languages/';

    private function getLanguageName( $code ){
        $result = 'English (?)';

        switch ($code){
            case 'nl':
                $result = 'Nederlands';
                break;

            default:
                $result = 'English';
        }

        return htmlentities($result);
    }
    
    public function getLangs()
    {
        $result = array();

        $all = glob("*.[a-z][a-z].php");
        foreach ($all as $i => $langf){
            $parts = explode('.', $langf, 2);
            $result[] = array(
                'id'   => $i,
                'name' => $this->getLanguageName($parts[1]),
                'flag' => $parts[1],
            );
        }

        return $result;
    }
    
}