<?php
session_start();
require_once "db.php";
require_once "lib.php";
$error = array();

if (!isset($_SESSION['resetid'])){header("Location: /send/start.php"); exit();}
else if ($_SESSION['resetid']===''){go_back();}


/*if (isset($_SESSION['resetid']))
{
	$resetid=$_SESSION['resetid'];
	//unset($_SESSION['resetid']);
	echo $resetid;
	//



}
else {
	 header("Location: /send/start.php");
     exit();
}
*/

if ((isset($_POST['n_passwrd']))&&(isset($_POST['cn_passwrd'])))
{
	if (($_POST['n_passwrd']!='')&&($_POST['cn_passwrd']!=''))
		{
			if ($_POST['n_passwrd']!=$_POST['cn_passwrd'])
				{	$error[]='Пароли не совпдают';}		
		}
	else {$error[]='Пароли не должны быть пустыми';}

	if ((empty($error))&&($_SESSION['resetid']!=''))
	{
		$chek_pass= chek_post($connection, $_POST['n_passwrd']);
		$passwrd=$config['pass_salt'].$chek_pass;
	    $passwrd_h=password_hash($passwrd, PASSWORD_DEFAULT);
	    $query="UPDATE `user_tab`  SET `password` = '".$passwrd_h."' WHERE `id`='".$_SESSION['resetid']."'";
        	if ($all_data = mysqli_query($connection,$query))
        		{echo "Можем менять";
		       unset($_SESSION['resetid']);}
		
	}
}




require_once "head.php";
//<span></span>
?>

<br/>
<br/>
  
  <br/>
<form class="editform" name="editform" method="post" >
    <p><input placeholder='Input New Password' type="password" name="n_passwrd"/></p>
   <p><input placeholder='Сonfirm New Password' type="password" name="cn_passwrd"/></p>
   <p><input type="submit" value="Изменить пароль" name="do_post"></p>
</form>
<br/>
 <p><a href="start.php">Главная</a></p>
 <br/>
 <?php if (!empty($error)){echo "<br/><br/><span class='iserror'>".$error[0]."</span><br/>";}?>
