<?php
namespace Book24;

use Main\App as App;

class Import {
    private $settings = array();
    
    function __construct(){
        $this->settings = App::getGroupSettings('book24');
    }
    
    private function AddError($param) {
        ;
    }
    
    private function checkAuthSettings( \Main\Result &$result ) {
        if( !is_array($this->settings) ){
            $result->AddError('import Book24: неверный формат настроек для импорта.')->SetStatus(false);
        }
        else{
            if(!isset($this->settings['key']) || $this->settings['key'] == ''){
                $result->AddError('import Book24: не найден параметр "key".')->SetStatus(false);
            }
            if(!isset($this->settings['partnerId']) || $this->settings['partnerId'] == ''){
                $result->AddError('import Book24: не найден параметр "partnerId".')->SetStatus(false);
            }
        }
        return $result->GetStatus();
    }
    
    public function ImportSectionData($selectParams= array()) {
        
        $result = new \Main\Result;
        do{
            if( $this->checkAuthSettings( $result ) === false ){
                break;
            }
            
            $objXmlImport = new Import\ImportXml( $this->settings );
            $objXmlImport->ImportSectionData( $result, $selectParams );
        }
        while(false);
                
        return $result;
    }
}