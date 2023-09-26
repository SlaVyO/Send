<?php
session_start();
require_once "lib.php";
if (isset($_POST['go_exit']))
	{
		unset($_SESSION['nikname']);
		unset($_SESSION['autorize']); 
		session_destroy();	
		//session_write_close();
	}
	
if (isset($_POST['go_prof']))
	{
		
		$host  = $_SERVER['HTTP_HOST'];
		$host  ="http://".$host."/send/profedit.php";
		header("Location: ".$host);
		exit(); 
	}


if ((!isset($_SESSION['nikname']))
	||($_SESSION['autorize']==false))
	{ 
		go_back() ;
  	}

if (isset($_SESSION['nikname']))
	{ 
		if ($_SESSION['nikname']!="")
			{
				$nikname=$_SESSION['nikname'];
			}
		else { go_back();}
  	}

require_once "head.php";
?>

	<script type="text/javascript">
		function scrooltofloor()
		{
  		var objDiv = document.getElementById("mytextbody");
		objDiv.scrollTop = objDiv.scrollHeight;
		}
	</script>
	</head>

<body onload="scrooltofloor()">
<div class='textbody' id='mytextbody' >
<?php

$filename="outres2.txt";
$from_tx = array("date--", "--date","----msgstart","----msgstop","nik--","--nik");
$to_tx  = array("<span class='mytime'>", "</span>","<div class='msg'>","</div><br/>","<span class='text-nikname'>", "</span>");
if (isset($_POST['text']))
	{
		$msgtext=$_POST['text'];
		$today = date("Y-m-d H:i:s"); 
	if (!file_exists($filename)){ $f_oparam="w";} 
	else { $f_oparam="a";} 
	$fp = fopen($filename, $f_oparam);
	if ($fp)
		{
		fwrite($fp,"----msgstart\r\n");	
		if ($nikname!=""){fwrite($fp,"nik--".$nikname."--nik\r\n");	}
		$test = fwrite($fp, $msgtext."\r\n");	
		fwrite($fp, "date--".$today."--date\r\n");
		fwrite($fp,"----msgstop\r\n");	
	 	fclose($fp);
		}
	else {echo "error file open";}
}
	
	$f1 = fopen($filename,"r");
	if ($f1)
		{
			while(!feof($f1))
				{	
					$gettext=fgets($f1);
					$gettext=str_replace("\r\n", "", $gettext);
					if (in_array($gettext,$from_tx)
						|| (strpos($gettext, "date--")!==false)
						||(strpos($gettext, "nik--")!==false))
						{$newtext = str_replace($from_tx, $to_tx, $gettext);}
					else{$newtext ="<p>".$gettext."</p>";} 
					echo $newtext;
				}
		fclose($f1);
		}
	else {echo "error file open to read";}
?>

</div>
<span class='form_nikname'>Username - <?php echo $nikname."</br>"; ?></span>
<form class='exitform' name="exitform" method="post" >
<input class="prfedit" type="submit" value="Настройки" name="go_prof">
<input type="submit" value="Выход" name="go_exit">
 </form>
 <form name="myform" method="post"  style="max-width: 600px;">
    <p><textarea placeholder="Input any text" name="text" rows="3" cols="72"></textarea></p>
   <p><input type="submit" value="Отправить" style="float: right;"></p>
 </form>

 </body>
</html>

