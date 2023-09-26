<?php
session_start();
require_once "db.php";
require_once "lib.php";
$error= array( );


//добавить обработку ошибки от перехорда пользователя на страницу минуя форму
//добавить обработку если пользователь уже залогинен в системе и перекидывать его на главную
//или просто оставить сообщение что залогинен и скрыть форму
////// проверять пришло ли что либо от нажатой кнопки. и если не нажата кнопка не возвращать вообще ничего

if (isset($_POST["dopos"]))
{
if ((isset($_SESSION['nikname']))&&($_SESSION['autorize']==true))
    { 
        $error[]="Сейчас вы зарегистрированны в системе </br>Нельзя регистрировать новых пользователей не выйдя из системы";
    }

if (!isset($_POST["nikname"])||(trim($_POST["nikname"])=="")) 
    { $error[]="Не укзано имя пользователя"; }

if (!isset($_POST["email"])||($_POST["email"]=="")) 
    { $error[]="Не указан email";}
else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
        {$error[]="email указан не верно";}

if (!isset($_POST["passwrd"])||($_POST["passwrd"]=="")) 
    { $error[]="Не указан пароль";}

if (!isset($_POST["passwrd_cnf"])||($_POST["passwrd_cnf"]=="")) 
    { $error[]="Пароль не подтвержден";}

if (isset($_POST["passwrd"])
    &&isset($_POST["passwrd_cnf"])
    &&($_POST["passwrd_cnf"]!=$_POST["passwrd"])) 
    { $error[]="Пароли не совпадают";}
// $quer_nik= chek_post($connection, $nikname);
if ($connection&&(empty($error)))
    {
        $chek_nik  = chek_post($connection, $_POST["nikname"]);
        $chek_email= chek_post($connection, $_POST["email"]);
        $chek_pass = chek_post($connection, $_POST["passwrd"]);

        $query="SELECT id FROM user_tab WHERE login ='".$_POST["nikname"]."'";
        if ($login_id = mysqli_query($connection,$query))
            {
                if (mysqli_num_rows($login_id) > 0) 
                { $error[]="Пользователь с таким именем уже существует";}
            }
        
        $query="SELECT id FROM user_tab WHERE email ='".$_POST["email"]."'";
        if ($email_id = mysqli_query($connection,$query))
        {
            if (mysqli_num_rows($email_id) > 0) 
            { $error[]="Пользователь с таким email уже существует";}
        }
    }


if (empty($error))
{
    $activation=activation_gen($chek_email,$config['pass_salt']);
    $flag=false;
    $flag_a=false;
    $ins_id =0;
    $passwrd=$config['pass_salt'].$chek_pass;
    $passwrd_h=password_hash($passwrd, PASSWORD_DEFAULT);

    if ($connection)
        {
            

           /* "INSERT INTO ser_tab 
        (login, password, email, regdate) VALUES
        ('".$_POST["nikname"]."','".$passwrd_h."','".$_POST["email"]."', NOW())");*/
        //осмыслить и переписать
            $query="INSERT INTO user_tab 
            (login, password, email, regdate) VALUES
            ('".$chek_nik."','".$passwrd_h."','".$chek_email."', NOW())";
            if ($flag=mysqli_query($connection,$query))
                {
                    $ins_id =mysqli_insert_id($connection);
                    if ($ins_id!=0)
                        {     
                            $query="SELECT * FROM user_activ WHERE id ='".$ins_id."'";
                            if ($activ_id = mysqli_query($connection,$query))
                                {
                                    if (mysqli_num_rows($activ_id) > 0) 
                                    { $error[]="ошибка регистрации пользователя";}
                                    else 
                                    {
                                        $query="INSERT INTO user_activ (id, token) VALUES ('".$ins_id."','".$activation."')";
                                        if (!$flag_a=mysqli_query($connection,$query))
                                            {$error[]="ошибка добавления в базу";}           
                                    }
                                }
                                else {$error[]="ошибка добавления в базу";}
                        }
                        else {$error[]="ошибка добавления в базу";}
                }
            else {$error[]="ошибка добавления в базу";}

        }

    if ($flag&&$flag_a){
        $result=array(
        'status' => 'OK'
       ,'error'=>$flag_a
       //,'error2'=>$flagr
    );   }
  else 
    {
        $result=array(
        'status' => 'DB connect Error'
       // ,'error'=>$error[0]
    );}
  echo json_encode($result); 
}
else 
{
   $result=array(
        'status' => 'Error',
        'error'=>$error[0]
    );
  echo json_encode($result);   
}
}
else 
{
  go_back();  
}

	
?>
