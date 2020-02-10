<?php
namespace Main;

class Template{
    protected static $template_dir = 'app/view/';
    
    public static function GetTemplateDir(){
        return App::getRootDir() . self::$template_dir;
    }
    
    public function IncludeTemplate($temp_path = '', $data = array(), $controller){
        $template_dir = self::GetTemplateDir();
        
        if( file_exists($template_dir . $temp_path .'.phtml') ){
            include $template_dir . $temp_path .'.phtml';
        }
        else {
            throw new \Exception('template '. $temp_path .' not find. ');
        }
    }
}
?>