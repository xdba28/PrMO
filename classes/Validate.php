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
			$ccc = 0;
            foreach($items as $item => $rules){
               foreach($rules as $rule => $rule_value){
                   
                   $value = trim($source[$item]);
                   
                   if($rule === 'required' && empty($value)){

                       $this->addError("{$item} is required");

                   }else if(!empty($value)){

                       switch($rule){
                           case 'min':
                                if(strlen($value) < $rule_value){                                    
									// $this->addError("{$item} must be a minimum of {$rule_value} characters.");
									$this->addError("Password must be a minimum of {$rule_value} characters.", "PASSWORD_MIN_REQUIREMENT");
                                }
                                break;
                           case 'max':
                                if(strlen($value) > $rule_value){                                    
									// $this->addError("{$item} must be a maximum of {$rule_value} characters.");
									$this->addError("Password must be a maximum of {$rule_value} characters.", "PASSWORD_MAX_REQUIREMENT");
                                }                           
                                break;
                           case 'matches':
                                if($value != $source[$rule_value]){
									// $this->addError("{$rule_value} must match {$item}.");
									$this->addError("New password doesn't match with re-typed password.", "PASSWORD_MATCHING");
                                }                           
                                break;
                           case 'unique': //username 
								$check = $this->db->get($rule_value, array('username', '=', $value));//the 'username' is the field in the $rul "edr_account" table in the database, also $rule_value here = "edr_account"								
                                    if($check->count()){
										$this->addError("Username already exists, transaction cannot be completed.", "USERNAME_VALIDATION_ERROR");
                                    }
								break;
                           case 'unique_prnl_username': //username 
								$check = $this->db->get("prnl_account", array('username', '=', $value));//the 'username' is the field in the $rul "edr_account" table in the database, also $rule_value here = "edr_account"								
                                    if($check->count()){
										$this->addError("Username already exists, transaction cannot be completed.", "USERNAME_VALIDATION_ERROR");										 
                                    }
                                break;								
                           case 'unique_prnl_id': // check the uniqueness of the employee ID of personnel
                                $check = $this->db->get("personnel", array('prnl_id', '=', $value));
                                    if($check->count()){
										$this->addError("Employee ID already exists, transaction cannot be completed.", "PRNLID_VALIDATION_ERROR");										
                                    }
                                break;                                
                           case 'unique_edr_id': //edr_id from endusers
                                $check = $this->db->get("enduser", array('edr_id', '=', $value));
                                    if($check->count()){
										$this->addError("Employee ID already exists, transaction cannot be completed.", "EDRID_VALIDATION_ERROR");
										
                                    }
                                break;
                           case 'unique_edr_email': //edr_email from endusers
                                $check = $this->db->get("enduser", array('edr_email', '=', $value));
                                    if($check->count()){
										$this->addError("Email address '{$value}' already exists, transaction cannot be completed.", "EMAIL_VALIDATION_ERROR");
										
                                    }
                                break;
                           case 'unique_prnl_email': //prnl_email from personnel
                                $check = $this->db->get("personnel", array('prnl_email', '=', $value));
                                    if($check->count()){
										$this->addError("Email address '{$value}' already exists, transaction cannot be completed.", "EMAIL_VALIDATION_ERROR");
                                    }
								break;
                           case 'validate_password': //check if submitted password is the same from the original
                                $check = $this->db->get($rule_value, array('account_id', '=', Session::get(Config::get('session/session_name'))));
                                    if($check->count()){
										
										if($check->first()->userpassword === Hash::make($value, $check->first()->salt)){
											// nothing
										}else{
											$this->addError("Submitted password doesn't match with your current password.", "INCORRECT_PASSWORD");
										}
										
										
										
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

        private function addError($error, $index = null){
			if(is_null($index)){
				$this->errors[] = $error;
			}else{
				$this->errors[$index] = $error;
			}
        }

        public function errors(){
            return $this->errors;   
        }

        public function passed(){
            return $this->passed;
        }
        
    }

?>