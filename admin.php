<?php
session_start();
require_once "lib.php";
require_once "db.php";
$blok= array( );
$notblok= array( );

function create_table($quer_array,$status)
{
if (!empty($quer_array))
	{
		if ($status==0){$head="НЕлоченные";$tabhead="Заблок-ть";}
		else  {$head="Лоченные";$tabhead="Разблок-ть";}
		$isblok=" class='bloked'";
		$isnoblok=" class='nobloked'";
		echo "</br><form method='post'>";
		echo "<span>".$head."</span>";
		echo "<table class='userbord'>";
		echo "<tr class='tabhead' ><th>Логин</th><th>Почта</th><th>Блок</th><th>".$tabhead."</th></tr>";
		$ind=-1;
		foreach ($quer_array as $value) 
		{
			$ind==1 ? $tdclass="class='cgrey'": $tdclass="class='cwhite'";
		 	echo "<tr ".$tdclass." ><td>".$value['login']."</td><td>".$value['email']."</td><td
		 	".($value['blok']==false ? $isnoblok.'>Нет': $isblok.'>Да' )."</td><td><input type='checkbox' name='formDoor[]' value='".$value['id']."' /></td></tr>";
		 $ind*=-1;	
		} 

		echo "</table>";
	echo "<input type='submit' name='formSubmit' value='Внести изминения' /></form></br>";
	}	

}


if (isset($_POST['go_exit']))
	{
		unset($_SESSION['admin_nik']);
		unset($_SESSION['admin_autorize']); 
		session_destroy();	
		//session_write_close();
	}


if (isset($_POST['formDoor']))
{
	$_SESSION['formDoor']=$_POST['formDoor'];
	unset($_POST['formDoor']);
	 header("Location: ".$_SERVER["REQUEST_URI"]);
  exit;
}

if (isset($_SESSION['formDoor']))
{
	$upd=$_SESSION['formDoor'];
	
	//$upd=$_POST['formDoor'];

	if (($connection)&&(!empty($upd))){	
			$escaped_row = array_map(array($connection, 'real_escape_string'), $upd);
			$upd = join($escaped_row, ', ');
			$query="UPDATE `user_tab`  SET `blok` = not blok WHERE `id` IN (".$upd.")";
        	if ($all_data = mysqli_query($connection,$query))
        		{//echo "<br/>Done<br/><br/>";}
        	}
		}
		unset($_SESSION['formDoor']);
}


if (isset($_SESSION['admin_nik']))
{
$nikname=	$_SESSION['admin_nik'];
}
else $nikname="НЕ АВТОРЕЗИРОВАН!";

if ((!isset($_SESSION['admin_nik']))
	||($_SESSION['admin_autorize']==false))
	{ 
		//echo "НЕ АВТОРЕЗИРОВАН!";
		go_back();
  	}

else {
if ($connection)
{
$query="SELECT id, login, activ, blok, email FROM user_tab";
        if ($all_data = mysqli_query($connection,$query))
            {
            	//echo mysqli_num_rows($all_data)."</br>";
            	// MYSQLI_ASSOC
               /*$quer_data=mysqli_fetch_array($all_data, MYSQLI_ASSOC);
               	printf ( $all_data["login"], $all_data["email"]);
              /*foreach ($quer_data as $value) {
              	print_r($value); */
             // }
              while ($atr = mysqli_fetch_assoc($all_data))
              {
              	//print_r($atr);
              	if ($atr['blok']==true)
              		{$blok[]=$atr; }
              	else {$notblok[]=$atr; }
              	//echo "</br>";
              }
			}


/*print_r($notblok);
echo "</br>";
print_r($blok);
echo "</br>";*/

/* очищаем результаты выборки */
mysqli_free_result($all_data);

/* закрываем подключение */
mysqli_close($connection);
}
}
require_once "head.php";
?>
	</head>
<body>
<span class='form_nikname'>Username - <?php echo $nikname."</br>"; ?></span>
<form class='exitform' name="exitform" method="post" >
<input type="submit" value="Выход" name="go_exit">
</form>

<?php

	create_table($notblok, 0);//"Заблок-ть");
	create_table($blok, 1); //"Разблок-ть");


	
?>

 </body>
</html>





