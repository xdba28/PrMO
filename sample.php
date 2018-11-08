<?php

    // require 'core/outer-init.php';

    // $user = DB::getInstance()->update('enduser', 'edr_fname', 'Christian',array(

    //     'edr_ext_name' => 'XXXXX',
    //     'edr_lname'    => 'Sy'


    // ));
	
	
	//=======================================


    //$string = "NICO";
    //$rest = substr($string, 0, 1);

    //echo $rest;
		
    // $test = new Test();

    // $units = $test->units();
    
    
    // foreach($units as $unit){
    //     if ($unit->acronym == ""){
    //         $acronym =  "No acronym";
    //     }else{
    //         $acronym =  $unit->acronym;
    //     }
          
    //     echo $unit->office_unit, " - ", $acronym, "<br>" ;

    // }


   //$time = strtotime("2018-08-17 08:30:51");
    
    //echo date('l F j, Y g:i:sa'); 
    //echo date("h:i:sa");
	//echo date("l F j, Y / m:i:s A", $time);
	$max = 5;

	for($x=1; $x<=$max; $x++){

		for($y=0; $y<$x; $y++){

			echo "* ";
		}

		echo "<br>";

		if($x ==  $max){
			for($x=1; $x<=$max-1; $x++){ //line
				for($y=$max; $y>$x; $y--){
					echo "* ";
				}
				echo "<br>";
			}
		}
	}

?>




