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
    }

?>