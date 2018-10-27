<?php

    class Redirect{

        private $db;

        public static function To($location){
            $character1 = substr($location, 0, 1);


            if(is_numeric($location)){
                $type = self::getType(Session::get(Config::get('session/session_name')));


                    if($type){
                        echo "1 if you enter here, this means that a user that has an admin session was trying to access the login page of a standard user";
                        switch($location){
                            case '404':
                                header('HTTP/1.0 404 Not Found');
                                include('includes/errors/404.php');
                                exit();
                                break;

                            case '403':
                                header('HTTP/1.0 403 Access Denied');
                                include('includes/errors/403.php');
                                exit();
                                break;

                            default :                    
                                exit();
                                break;
                        }
                    }else{
                        echo "0 if you enter here, this means that a user that has a standard user session was trying to access the login page of an administrator";
                        switch($location){
                            case '404':
                                header('HTTP/1.0 404 Not Found');
                                include('../includes/errors/-404.php');
                                exit();
                                break;

                            case '403':
                                header('HTTP/1.0 403 Access Denied');
                                include('../includes/errors/-403.php');
                                exit();
                                break;

                            default :                    
                                exit();
                                break;
                        }
                    }


                    
            }else{

                if($character1 == "-"){

                    $type = self::getType(Session::get(Config::get('session/session_name')));

                    switch($type){

                        case 'Super Admin':
                            header('Location: ../views/Super-admin/Dashboard'); 
                            exit();
                        break;

                        case 'Director':
                            header('Location: ../views/Director/Dashboard'); 
                            exit();
                        break;
                        
                        case 'Procurement Aid':
                            header('Location: ../views/Aid/Dashboard'); 
                            exit();
                        break;

                        case 'Staff':
                            header('Location: ../views/Staff/Dashboard'); 
                            exit();
                        break;

                    }

                }else{
                    header('Location:' . $location);
                }
  


            }

        }

        public static function getType($id){

            $db = DB::getInstance();
            $user = $db->query_builder("SELECT name FROM `prnl_account`, `group` WHERE prnl_account.group_ = group.group_id AND account_id = '{$id}' ");

                if($user->count()){
                    return $user->first()->name;
                }

                return false;
        }

    }

?>