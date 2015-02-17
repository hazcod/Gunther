<?php

class Load
{
    const MODELPATH = 'models/';
    const TEMPLATEPATH = 'views/';
    const LAYOUTFOLDER = '_layouts/';
    const PARTIALFOLDER = '_partials/';

    private static function _addExtension($filename)
    {
        return (substr($filename, -4) == '.php') ? $filename : $filename . '.php';
    }

    public static function model($modelname)
    {
        $modelfile = APPLICATION_PATH . self::MODELPATH . self::_addExtension($modelname);

        if (file_exists($modelfile)) {
            require_once($modelfile);
            return new $modelname();
        } else {
            error_log("Model not found: $modelname. Should be at location $modelfile.");
            exit;
        }
    }

    public static function view($viewname)
    {
        $viewfile = APPLICATION_PATH . self::TEMPLATEPATH . self::_addExtension($viewname);

        if (file_exists($viewfile)) {
            return $viewfile;
        } else {
            error_log("View not found: $viewname. Should be at location $viewfile.");
            exit;
        }
    }

    public static function layout($layoutname)
    {
        $layoutfile = APPLICATION_PATH . self::TEMPLATEPATH . self::LAYOUTFOLDER . self::_addExtension($layoutname);

        if (file_exists($layoutfile)) {
            return $layoutfile;
        } else {
            error_log("View not found: $layoutname. Should be at location $layoutfile.");
            exit;
        }
    }

    public static function partial($partialname)
    {
        $partialfile = APPLICATION_PATH . self::TEMPLATEPATH . self::PARTIALFOLDER . self::_addExtension($partialname);

        if (file_exists($partialfile)) {
            return $partialfile;
        } else {
            error_log("View not found: $partialname. Should be at location $partialfile.");
        }
    }
}