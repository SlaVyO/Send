<?php
session_start();
require_once "db.php";
require_once "lib.php";
$error=array();
if (!isset($_GET['code']))
	{
		$error[]="HTTP/1.1 404 Not Found";        
		
	}
else if ($_GET['code']!="")
	{
		if ($connection){
			$chek_code = chek_post($connection, $_GET['code']);
			$query="SELECT id FROM user_passress WHERE token ='".$chek_code."'";
        if ($get_query = mysqli_query($connection,$query))
            {
                if ((mysqli_num_rows($get_query)==0)||(mysqli_num_rows($get_query)>1)) 
                { $error[]="Ошибка инициализации пользователя";}
            	else {
            			$get_id=mysqli_fetch_assoc($get_query);
            			$_SESSION['resetid']=$get_id["id"];
                        /*$host  = $_SERVER['HTTP_HOST'];
                         $host  ="http://".$host."/send/admin.php";*/
                         //нужна ли вычистка из таблицы сброса или вычищать уже после сброса?
                         $query ="DELETE FROM user_passress WHERE id ='".$get_id["id"]."'";
                        if (mysqli_query($connection,$query))
                            {
                                header("Location: /send/newpass.php");
                                exit();
                            }
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
