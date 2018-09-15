<?php

        if($_SESSION['accounttype'] == '1'){
            echo '<script> var newuser = true; </script>';
        }else{
            echo '<script> var newuser = false; </script>';
        }


?>