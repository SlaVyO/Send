<?php
require_once "db.php";
require_once "lib.php";
$error=array();
if (!isset($_GET['code']))
	{
		echo "HTTP/1.1 404 Not Found";        
		exit();
	}
else if ($_GET['code']!="")
	{
		if ($connection){
			$chek_code = chek_post($connection, $_GET['code']);
			$query="SELECT id FROM user_activ WHERE token ='".$chek_code."'";
        if ($get_query = mysqli_query($connection,$query))
            {
                if ((mysqli_num_rows($get_query)==0)||(mysqli_num_rows($get_query)>1)) 
                { $error[]="Ошибка активации пользователя";}
            	else {
            			$get_id=mysqli_fetch_assoc($get_query);
            			//$query="SELECT * FROM user_tab WHERE id ='".$get_id["id"]."'";

            			$query="UPDATE user_tab SET activ = '1'  WHERE id = '".$get_id["id"]."'";
        				if ($get_query = mysqli_query($connection,$query))
            				{
            					
            					$query ="DELETE FROM user_activ 
            							WHERE token ='".$chek_code."' and  id = '".$get_id["id"]."'";
            					
        						if ($get_query = mysqli_query($connection,$query))
		            				{
		            					$error[]="Ваша учетная запись успешно активирована";
									}
								else { $error[]="Ошибка активации пользователя";}
   	           				}
            			else { $error[]="Ошибка активации пользователя";}	
            				
            				
            		}
            }
		}
		else {$error[]="connect error";}
		
	}
$error_msg=$error[0];
require_once "head.php";

?>
</head>

<body>
</br>
</br>
<span class="servise_msg"><?php echo $error_msg; ?></span>
</br>
 <span class="servise_msg"><a href="start.php">На главную</a></span>
</body>
</html>
