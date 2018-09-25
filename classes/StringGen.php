<?php

    class StringGen{	

        public static function generate(){

            $string = 'ABCDEFGHIJ1234567890';
            $length = strlen($string);

            $random_string = '';

            for($x=0; $x<2; $x++){
                for($y=0; $y<3; $y++){
                    $random_string .= $string[rand(0, ($length - 1))];
                }
            }
            
           return $random_string;
        }   
        
        public static function password(){
            $temp = 'ABCDEFGHIJKLMNOPQRSTUV12345678901234567890';
            $length = strlen($temp);

            $string = '';

                for($x=0; $x<8; $x++){
                    $string .= $temp[rand(0, ($length - 1))];
                }

           return $string;
		}
		
		public static function projectRefno($type){

			$db = DB::getInstance();
			$currentYear = date('Y');

			if($db->query_builder("SELECT COUNT(*) AS 'series' FROM `projects` WHERE project_ref_no LIKE '%{$currentYear}%' AND project_ref_no LIKE '%{$type}%'")){
				$series = $db->first()->series;
			}

			$finalSeries = $series + 1;

			$refno =  $type.$currentYear."-".$finalSeries;
			return $refno;
			
		}

    }

?>