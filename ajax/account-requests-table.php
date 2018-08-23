<?php

    session_start();
    date_default_timezone_set('Asia/Manila');

    $GLOBALS['config'] = [
        'mysql'     =>[
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => '',
            'db'       => 'bubac_prmo'
        ],
        'session'  =>[
            'session_name'  => 'user',
            'token_name'    => 'token'
        ],
        'remember' =>[
            'cookie_name' => 'hash',
            'cookie_expiry' => 604800
        ]
    ];


    spl_autoload_register(function($class){
        require_once "../classes/{$class}.php";
    });


$sa = new Super_admin();
$requests = $sa->requests();
$registered = $sa->registered_users();


?>

	

<table class="footable table table-stripped toggle-arrow-tiny">
    <thead>
    <tr>

        <th data-toggle="true">Requestor</th>
        <th>Unit</th>
        <th>Status</th>
        <th data-hide="all">Phone</th>
        <th data-hide="all">Email</th>                                                        
        <th data-hide="all">Requested</th>
        <th data-hide="all">Employee Id</th>
        <th data-hide="all">Remarks</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    
    <?php														
        
        foreach($requests as $request){
            if($request->ext_name == "none"){
                $fullname = $request->fname." ".$request->last_name;
            }else{
                $fullname = $request->fname." ".$request->last_name." ".$request->ext_name;
            }
            
            $color = ($request->status == "pending") ? "text-navy" : "text-danger";
            $color1 = ($request->remarks == "none") ? "" : "text-danger";
            
            
            $time = strtotime($request->submitted);
            $final = date("l F j, Y g:i:sa", $time); 
            
        //<td><span class="pie">90/100</span></td>	
            echo '
                <tr>
                    <td>'.$fullname.'</td>
                    <td>'.$request->office_name.'</td>
                    <td><a class="'.$color.'">'.$request->status.'</a></td>
                    <td>'.$request->contact.'</td>
                    <td>'.$request->email.'</td>																					
                    <td>'.$final.'</td>
                    <td><b>'.$request->employee_id.'</b></td>
                    <td><a class="'.$color1.'">'.$request->remarks.'</a></td>
                    <td>
                    <a onclick="approve(\''.$request->ID.'\')"><i class="fa fa-check text-navy"></i></a> 
                        
                        <a data-toggle="modal" data-target="#decline_modal" onclick="ps_mdl_d(\''.$fullname.'\', \''.$request->ID.'\')">
                            <i class="fa fa-close text-danger" style="margin-left:20px"></i>
                        </a>
                    </td>
                </tr>																				
            ';
        }
    ?>
    

    </tbody>
    <tfoot>
    <tr>
        <td colspan="5">
            <ul class="pagination float-right"></ul>
        </td>
    </tr>
    </tfoot>
</table>


