<?php
session_start();
require_once "db.php";
require_once "lib.php";
$error = array();
$query_arr= array();
$query1="";

if ((!isset($_SESSION['nikname']))
	||($_SESSION['autorize']==false))
	{ 
		go_back() ;
  	}


if ($connection)
    {
    	
        //$quer_nik= chek_post($connection, $nikname);
        $query="SELECT * FROM user_tab WHERE login ='".$_SESSION['nikname']."'";
        if ($all_quer = mysqli_query($connection,$query))
            {
                
            	if (mysqli_num_rows($all_quer)==1){

            		$quer_array=mysqli_fetch_assoc($all_quer);

                if (isset($_POST['do_post']))//если нажата кнопка проверяем что 
                                              //пришло и вносим в базу
                {
                  if (isset($_POST['email'])) 
                    {
                      $chek_email=chek_post($connection, $_POST['email']);
                      if (($chek_email!="")&&($chek_email!=$quer_array["email"]))
                      {
                        
                        $query="SELECT * FROM user_tab WHERE email ='".$chek_email."'";
                           if ($mail_quer = mysqli_query($connection,$query))
                            {
                              if (mysqli_num_rows($mail_quer)==0){
                                  $query_arr[]="email='".$chek_email."'"; }
                                else {$error[]="Такая почта уже используется";}  
                            }
                      }

                    }
                  if (isset($_POST['discript'])) 
                    {
                      $chek_discript=chek_post($connection, $_POST['discript']);
                      if (($chek_discript!="")&&($chek_discript!=$quer_array["discript"]))
                      {
                        $query_arr[]="discript='".$chek_discript."'";  
                      }
                    }
                  if (isset($_POST['alias'])) 
                    {
                      $chek_alias=chek_post($connection, $_POST['alias']);
                      if (($chek_alias!="")&&($chek_alias!=$quer_array["alias"]))
                      {
                        $query_arr[]="alias='".$chek_alias."'";  
                      }
                    }  
                  if ((isset($_POST['o_passwrd']))&&(isset($_POST['n_passwrd']))&&(isset($_POST['cn_passwrd']))) 
                    {
                      //echo "pass is sett</br>";
                      $chek_password_o=chek_post($connection, $_POST['o_passwrd']);
                      $passwrd=$config['pass_salt'].$chek_password_o;
                      
                      if (password_verify($passwrd, $quer_array['password']))
                        {
                          //echo "pass is verif</br>";
                          $chek_password_n=chek_post($connection, $_POST['n_passwrd']);
                          $chek_password_cn=chek_post($connection, $_POST['cn_passwrd']);
                          if (($chek_password_n==$chek_password_cn)&&($chek_password_n!=""))
                          {
                            //echo "pass is confirm</br>";
                            $passwrd=$config['pass_salt'].$chek_password_n;
                            $passwrd_h=password_hash($passwrd, PASSWORD_DEFAULT);
                            $query_arr[]="password='".$passwrd_h."'";  
                          }
                          else {$error[]="Новые пароли не совпадают или пусты";}//echo "pass not confirm</br>";}    

                        }
                      else {$error[]="Пароль введен не верно";}//echo "pass not verif</br>";}    
                      }
                      //else {echo "pass not sett</br>";}  

                    if (!empty($query_arr))
                    {
                      $query="UPDATE user_tab SET ";  
                      foreach ($query_arr as $arr_value) {
                        $query.=$arr_value.", ";
                      }
                      $query.="WHERE login ='".$_SESSION['nikname']."'";
                      $query = str_ireplace(", WHERE", " WHERE",$query );
                    }
                    //$query1=$query;
                      //добавить проверку на запроссс
                  if (mysqli_query($connection,$query))
                    {
                        $query="SELECT * FROM user_tab WHERE login ='".$_SESSION['nikname']."'";
                          if ($all_quer = mysqli_query($connection,$query))
                          {
                
                            if (mysqli_num_rows($all_quer)==1){

                            $quer_array=mysqli_fetch_assoc($all_quer);
                          }  
                        else if (mysqli_num_rows($all_quer)==0)
                        { $error[]="Пользователь с таким именем не зарегистрирован";}
                        else if   (mysqli_num_rows($all_quer )>1) 
                        { $error[]="Ошибка идентификации пользователя";}

                     }
            	}
            }
          }
                else if (mysqli_num_rows($all_quer)==0)
                { $error[]="Пользователь с таким именем не зарегистрирован";}
            	else if 	(mysqli_num_rows($all_quer )>1) 
                { $error[]="Ошибка идентификации пользователя";}

            }
     }


require_once "head.php";
?>
</head>
<body>
</br>
<span>Логин: <?php echo $quer_array["login"];?></span>
</br>
 <span>Дата регистрации: <?php echo $quer_array["regdate"];?></span>
  <?php if (!empty($error)){echo "<br/><br/><span class='iserror'>".$error[0]."</span><br/>";}?>
  <br/>
<form class="editform" name="editform" method="post" >
   <p>E-mail: <input placeholder='Input email address' type="email" name="email" value="<?php echo $quer_array["email"]; ?>"/></p>
   <p>Description: <input placeholder='Input description' type="discript" name="discript" value="<?php echo $quer_array["discript"]; ?>"/></p>
   <p>Alias: <input placeholder='Input alias' type="alias" name="alias" value="<?php echo $quer_array["alias"]; ?>"/></p>
   <p>Old Password:<input placeholder='Input Old Password' type="password" name="o_passwrd"/></p>
   <p>New Password: <input placeholder='Input New Password' type="password" name="n_passwrd"/></p>
   <p>Сonfirm New Password: <input placeholder='Сonfirm New Password' type="password" name="cn_passwrd"/></p>
   <p><input type="submit" value="Внести изминения" name="do_post"></p>
</form>
<br/>
 <p><a href="send3.php">Вернуться</a></p>





<?php
//echo "profile.edit";
/*print_r($quer_array);
echo "</br>";
print_r($error);
echo "</br>";
print_r($query);
echo "</br>";
print_r($query1);
/* $2y$10$e65ZCfJhoNXUpNNVSrNt1Oyv0Bzqc/qLEmkpAfzA2g7nK.oE08J9i*/
?>
 </body>
</html>




