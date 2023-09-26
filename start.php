<?php
session_start();
require_once "db.php";
require_once "lib.php";
$error= array( );

if (isset($_POST['nikname']))
	{
		if (($_POST['nikname'])!="")
		{ $nikname=trim($_POST['nikname']); }
	}		


//разобраться с никнеймами
function atorize($is_autorize,$nikname) 
	{ 
		if ($is_autorize==true)
		{
			if (!isset($_SESSION['nikname']))
	     		{$_SESSION['nikname'] = $nikname;}
	     	$host  = $_SERVER['HTTP_HOST'];
			$host  ="http://".$host."/send/send3.php";
			header("Location: ".$host);
			exit();
				
		}
	}
	 
if ((isset($_SESSION['autorize']))&&(isset($_SESSION['nikname'])))
	{
		atorize($_SESSION['autorize'],$_SESSION['nikname']);
	}

if (!isset($nikname)||(($nikname)==""))
	{ $error[]="Не укзано имя пользователя"; }

if (!isset($_POST['passwrd'])||($_POST['passwrd']==""))
	{ $error[]="Не указан пароль";}

if ($connection&&(empty($error)))
    {
    	$passwrd='';
    	$passwrd=$config['pass_salt'].$_POST['passwrd'];
        $quer_nik= chek_post($connection, $nikname);
        $query="SELECT id, activ, blok, password FROM user_tab WHERE login ='".$quer_nik."'";
        if ($login_data = mysqli_query($connection,$query))
            {
               
              if  (mysqli_num_rows($login_data)==1) 
                {
                  $quer_data=mysqli_fetch_assoc($login_data);
                    if ($quer_data['activ']==0)
                        { $error[]="Пользователь не активирован";}
                    if ($quer_data['blok']==1)
                     { $error[]="Пользователь заблокирован";}  
                    if (!password_verify($passwrd, $quer_data['password']))
                        { $error[]="Пароль указан не верно";}
                }      
                 else if (mysqli_num_rows($login_data)==0)
                { $error[]="Пользователь с таким именем не зарегистрирован";}
              else if  (mysqli_num_rows($login_data)>1) 
                { $error[]="Ошибка идентификации пользователя";}
              /*
            }
        $query="SELECT activ, blok FROM user_tab WHERE login ='".$quer_nik."'";
        if ($is_activ_id = mysqli_query($connection,$query))
            {
                   $quer_activ=mysqli_fetch_assoc($is_activ_id);
               	 		if ($quer_activ['activ']==0)
               	 		   	{ $error[]="Пользователь не активирован";}
                    if ($quer_activ['blok']==1)
                     { $error[]="Пользователь заблокирован";}
            }    


        $query="SELECT password FROM user_tab WHERE login ='".$quer_nik."'";
        if ($login_pass = mysqli_query($connection,$query))
            {
               	$quer_pass=mysqli_fetch_assoc($login_pass);
               	 		if (!password_verify($passwrd, $quer_pass['password']))
               	 		   	{ $error[]="Пароль указан не верно";}*/
            }
    }

if (empty($error))
    {
		$_SESSION['autorize']=true; 		
    	atorize($_SESSION['autorize'],$nikname);
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
   <p><input placeholder='Input Nikname' type="text" name="nikname" value="<?php echo @$_POST['nikname']; ?>" /></p>
   <p><input placeholder='Input Password' type="password" name="passwrd" /></p>
   <p><input type="submit" value="Вход" name="do_post"></p>
 </form>
 <span class="servise_msg"><a href="registr.html">Регистрация</a>&nbsp;&nbsp;<a href="reset.html">Забыл пароль</a></span>
</body>
</html>
