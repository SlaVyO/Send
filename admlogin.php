<?php 
session_start();
require_once "db.php";
require_once "lib.php";
$error= array( );

if (isset($_POST['admin_nik']))
	{
		if (($_POST['admin_nik'])!="")
		{ $nikname=trim($_POST['admin_nik']); }
	}		


//разобраться с никнеймами
function atorize($is_autorize,$nikname) 
	{ 
		if ($is_autorize==true)
		{
			if (!isset($_SESSION['admin_nik']))
	     		{$_SESSION['admin_nik'] = $nikname;}
	     	$host  = $_SERVER['HTTP_HOST'];
			$host  ="http://".$host."/send/admin.php";
			header("Location: ".$host);
			exit();
				
		}
	}
	 
if ((isset($_SESSION['admin_autorize']))&&(isset($_SESSION['admin_nik'])))
	{
		atorize($_SESSION['admin_autorize'],$_SESSION['admin_nik']);
	}

if (!isset($nikname)||(($nikname)==""))
	{ $error[]="Не укзано имя пользователя"; }

if (!isset($_POST['passwrd'])||($_POST['passwrd']==""))
	{ $error[]="Не указан пароль";}

if ($connection&&(empty($error)))
    {
    	$passwrd='';
    	$passwrd=$config['adm_pass_salt'].$_POST['passwrd'];
        $quer_nik= chek_post($connection, $nikname);
        $query="SELECT id, password, activ FROM admin_tab WHERE login ='".$quer_nik."'";
        if ($login_data = mysqli_query($connection,$query))
            {
              if (mysqli_num_rows($login_data)==1)
                {
                    $quer_data=mysqli_fetch_assoc($login_data);
                    if ($quer_data['activ']==0)
                        { $error[]="Пользователь не активен";}
                    if (!password_verify($passwrd, $quer_data['password']))
                        { $error[]="Пароль указан не верно";}
                }
              else if (mysqli_num_rows($login_data)==0)
                { $error[]="Пользователь с таким именем незарегистрирован";}
            	else if (mysqli_num_rows($login_data)>1) 
                { $error[]="Ошибка идентификации пользователя";}
           }
    }

if (empty($error))
    {
		$_SESSION['admin_autorize']=true; 		
    	atorize($_SESSION['admin_autorize'],$nikname);
	}
else 
	{	
		 $error_msg="";
		if (isset($_POST['do_post']))
		{print_r($error);
			 $error_msg=$error[0];
		}
	}

 
require_once "head.php";
?>


	</head>

<body>
</br>
</br>
<span class="servise_msg"><?php echo $error_msg; ?></span>
</br>
<form class="myform" name="myform" method="post" >
   <p><input placeholder='Input Nikname' type="text" name="admin_nik" value="<?php echo @$_POST['admin_nik']; ?>" /></p>
   <p><input placeholder='Input Password' type="password" name="passwrd" /></p>
   <p><input type="submit" value="Вход" name="do_post"></p>
 </form>

</body>
</html>

