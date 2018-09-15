<?php
//validate for creating new users by super admin
    class Validate{
        private $passed = false,
                $errors = array(),
                $db = null;

        public function __construct(){
            $this->db = DB::getInstance();
        }

        public function check($source, $items = array()){
            foreach($items as $item => $rules){
               foreach($rules as $rule => $rule_value){
                   
                   $value = trim($source[$item]);
                   
                   if($rule === 'required' && empty($value)){

                       $this->addError("{$item} is required");

                   }else if(!empty($value)){

                       switch($rule){
                           case 'min':
                                if(strlen($value) < $rule_value){                                    
                                    $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                                }
                                break;
                           case 'max':
                                if(strlen($value) > $rule_value){                                    
                                    $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                                }                           
                                break;
                           case 'matches':
                                if($value != $source[$rule_value]){
                                    $this->addError("{$rule_value} must match {$item}.");
                                }                           
                                break;
                           case 'unique': //username
                                $check = $this->db->get($rule_value, array('username', '=', $value));//the 'username' is the field in the $rul "edr_account" table in the database, also $rule_value here = "edr_account"
                                    if($check->count()){
                                         $this->addError("username already exist.");
                                    }
                                break;
                           case 'unique_prnl_id': // check the uniqueness of the employee ID of personnel
                                $check = $this->db->get($rule_value, array('prnl_id', '=', $value));
                                    if($check->count()){
                                         $this->addError("Employee Id already exist.");
                                    }
                                break;                                
                           case 'unique_edr_id': //edr_id from endusers
                                $check = $this->db->get($rule_value, array('edr_id', '=', $value));
                                    if($check->count()){
                                         $this->addError("Employee Id already exist.");
                                    }
                                break;
                           case 'unique_edr_email': //edr_email from endusers
                                $check = $this->db->get($rule_value, array('edr_email', '=', $value));
                                    if($check->count()){
                                         $this->addError("Email already exist.");
                                    }
                                break;
                           case 'unique_prnl_email': //prnl_email from personnel
                                $check = $this->db->get($rule_value, array('prnl_email', '=', $value));
                                    if($check->count()){
                                         $this->addError("Email already exist.");
                                    }
                                break;                                                                                                    
                       }
                   }

               }
            }

            if(empty($this->errors)){
                $this->passed = true;
            }
            return $this;
        }

        private function addError($error){
            $this->errors[] = $error;
        }

        public function errors(){
            return $this->errors;   
        }

        public function passed(){
            return $this->passed;
        }
        
    }

?>