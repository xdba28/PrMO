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

                        case 'super_admin':
                            header('Location: ../views/Super-admin/Dashboard.php'); 
                            exit();
                        break;

                        case 'director':
                            header('Location: ../views/Admin/Dashboard.php'); 
                            exit();
                        break;
                        
                        case 'aid':
                            header('Location: ../views/Aid/Dashboard.php'); 
                            exit();
                        break;

                        case 'staff':
                            header('Location: ../views/Staff/Dashboard.php'); 
                            exit();
                        break;

                    }

                }else{
                    header('Location:' . $location . '.php');
                }
  


            }

        }

        public static function getType($id){

            $db = DB::getInstance();
            $user = $db->query_builder("SELECT name FROM `prnl_account`, `group` WHERE prnl_account.group = group.group_id AND account_id = '{$id}' ");

                if($user->count()){
                    return $user->first()->name;
                }

                return false;
        }

    }

?>