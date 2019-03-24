<?php
$alphanumeric = "ABCD1234567890EFGHIJKLMNOPQRST1234567890UVWXYZabcdefghijklm1234567890nopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXY1234567890Zabcdefghijklmnopqrstuvwxyz1234567890";
			$length = strlen($alphanumeric);
			$string = '';

			for($x=0; $x<30; $x++){
				$string .= $alphanumeric[rand(0, ($length - 1))];
			}

			echo $string;