<?php
session_start();
require_once "db.php";
require_once "lib.php";
$error = array();

if (isset($_POST['do_post'])&&isset($_POST['email']))
{
	$_SESSION['resetmail']=$_POST['email'];
	unset($_POST['email']);
	 header("Location: ".$_SERVER["REQUEST_URI"]);
  exit;
}




if (isset($_SESSION['resetmail']))
{
	$resetmail=$_SESSION['resetmail'];

	if ($resetmail!="")
		{
			if ($connection)
			{
				$check_mail=chek_post($connection, $resetmail);
				$query="SELECT id FROM user_tab WHERE email ='".$check_mail."'";
				if ($id_quer = mysqli_query($connection,$query))
            		{
                 		/*if (mysqli_num_rows($all_quer)==1){
            				
            			}
            			else */
            				unset($_SESSION['resetmail']);
            			if (mysqli_num_rows($id_quer)>1){
            				$error[]="Не удалось идентифицировать пользователя";
            			}
            			else if (mysqli_num_rows($id_quer)==0){
            				$error[]="Пользователь не зарегистрирован";
            			}
					}
			}		
		}
		else {$error[]="Не корректно заполнен адрес почты";}
}
else {$error[]="Системная Ошибка";}

if (!empty($error)){	echo $error[0];	}
else{
	//генерим строку
	$resetlink=activation_gen($check_mail,$config['pass_salt']);
	$quer_id=mysqli_fetch_assoc($id_quer);
	//внросим в базу
	//добавить проверку на наличие в базе сброса id и добавить подчистку предыдущих ссылок для сброса
		$query ="DELETE FROM user_passress WHERE id ='".$quer_id['id']."'";
		 mysqli_query($connection,$query);
		
	$query="INSERT INTO user_passress (id, token) VALUES ('".$quer_id['id']."','".$resetlink."')";
		if ($flag_a=mysqli_query($connection,$query))
	        {
	        	echo "Сыллка для сброса пароля была отправлена на указанный вами адрес";
	        /*
	        отправляем ссылку 
	        http://.../send/passreset.php?code=$resetlink
	        по адрессу $chek_email

	        */}
        else {$error[]="ошибка добавления в базу";} 
if (!empty($error)){echo $error[0];	}
}

?>
