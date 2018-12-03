<?php

namespace Addon\Controller;

class Addon{
   
    protected $view = null;

    public $addon_path          =   '';


    public function __construct(){
        $this->view         =   \Think\Think::instance('\Think\View');
        $this->addon_path   =   ADDON_PATH.'/'.$this->getName().'/';
    }

    final protected function theme($theme){
        $this->view->theme($theme);
        return $this;
    }

    final protected function display($template=''){
        if($template == '')
            $template = CONTROLLER_NAME;
        echo ($this->fetch($template));
    }
    
    final protected function assign($name,$value='') {
        $this->view->assign($name,$value);
        return $this;
    }
    
    final protected function fetch($templateFile = CONTROLLER_NAME){
        if(!is_file($templateFile)){
            $templateFile = $this->addon_path.$templateFile.C('TMPL_TEMPLATE_SUFFIX');
            if(!is_file($templateFile)){
                throw new \Exception("模板不存在:$templateFile");
            }
        }
        return $this->view->fetch($templateFile);
    }
    
    final public function getName(){
        $class = get_class($this);
        return substr($class,strrpos($class, '\\')+1, -5);
    }

}
