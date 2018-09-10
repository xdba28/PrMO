<?php
$time = date('r');
echo "data: The server time is: $time\n\n";
echo "<pre>",print_r(json_decode($_POST['obj'], true)),"</pre>";
flush();
?>