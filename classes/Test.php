<?php

    class Test{

        private $db = null,
                $data;


        public function __construct(){  //should be copied in user.php class * always inst
            $this->db = DB::getInstance();
        }        

        public function AllUnits(){
            if ($this->db->query_builder("SELECT * FROM units")) 
            return $this->db->results();
        }      
        
        











        
        public function data(){
            return $this->data;
        }



    }




?>