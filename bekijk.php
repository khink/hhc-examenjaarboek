<?PHP 

include("includes/functions.php");
include("includes/appadmin_auth.php");

if (login_beheer($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])) {
  if ($nr = $_GET['nr']) {
    $html=leerlingscherm($nr);
  } else {
    if ($_GET['overzicht']) {
      $html = allespagina();
    } else {
	   if ($_GET['docoverzicht']) {
	     $html = docallespagina();
	   }
	   else 
	     $html=beheerscherm();
    }
  }
} else {
  authenticate();
}

?>

<HTML>
<HEAD>
<TITLE>Examenboek Het Hogeland College - beheer</TITLE>
</HEAD>
<BODY>

<FONT FACE="verdana,arial" SIZE=-1>

<?PHP print $html; ?>

</BODY>
</HTML>
