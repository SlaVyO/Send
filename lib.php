<?php
function chek_post($link, $var)
{
    return mysqli_real_escape_string( $link,  $var );
}

function activation_gen($email,$salt)
{
	return md5($email.time().$salt);
}

function go_back() 
	{
		$host  = $_SERVER['HTTP_HOST'];
		$host  ="http://".$host."/send/start.php";
		header("Location: ".$host);
		exit(); 
	}	

?>
