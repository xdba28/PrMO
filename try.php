<?php

// $array = array('lastname', 'email', 'phone');
// $comma_separated = "'" .implode("', '", $array).  "'";

// echo $comma_separated; // lastname,email,phone

$myarray = array();

$arr1 = array("a" => 1, "b" => 2, "c" => 3);
$arr2 = array("x" => 4, "y" => 5, "z" => 6);

foreach ($arr1 as $key => &$val) { //this 

    //echo $val,"<br>";
    array_push($myarray, $val);

}



//eprint_r($arr1);
  echo '<pre>',print_r($myarray),'</pre>';
  $sample = "'". implode("', '", $myarray) ."'";

  echo $sample;


  session_start();

  echo $_SESSION['user'] = '2015-15096';
?>