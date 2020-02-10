<?php
namespace Main;

class Result {
    private $status = NULL;
    private $errors = array();
    private $data = NULL;
    
    
    public function GetStatus() {
        return $this->status;
    }
    public function GetErrors() {
        return $this->errors;
    }
    public function GetData() {
        return $this->data;
    }
    
    public function AddError($errorStr = '', $errorCode = '') {
        if($errorCode != '' && !is_numeric($errorCode) ){
            $this->errors[ $errorCode ] = $errorStr;
        }
        else{
            $this->errors[] = $errorStr;
        }
        return $this;
    }
    
    public function AddErrors($arErrors = array() ) {
        if(is_array($arErrors)){
            foreach($arErrors as $errorCode => $errorStr){
                $this->AddError($errorStr, $errorCode);
            }
        }
        return $this;
    }
    
    public function SetStatus($statusVal = false) {
        $this->status = !!$statusVal;
        return $this;
    }
    
    public function SetData($data = NULL) {
        $this->data = $data;
        return $this;
    }

}