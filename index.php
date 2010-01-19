<HTML>
<HEAD>
<TITLE>Examenboek Het Hogeland College</TITLE>
</HEAD>
<BODY>

<TABLE WIDTH=100% CELLPADDING=20>
<TR><TD VALIGN=top>
<FONT FACE="verdana,arial" SIZE=-1>
<!-- LINKERBALK -->

<?PHP 

include("includes/functions.php");
/* logger(); */

$wijz = isset($_POST['gegevenswijzigen']);
$opst = isset($_POST['gegevensopsturen']);

$ingelogd=ingelogd($_POST['stamnr'],$_POST['wachtwoord']);
if (!$ingelogd[0]) {
  $html = htmlloginscherm();
}
else {
  $gegevens=join(" ",$ingelogd);
  $html = htmlingelogdals($gegevens);
}

$html.="<HR>\n";
$html.="<FONT SIZE=-2>\n";
$html.=afteller();
$html.="</FONT>\n";

print $html;

?>

<!-- EINDE LINKERBALK -->
</TD>
<TD WIDTH=100% VALIGN=top>
<!-- HOOFDVELD -->
<FONT FACE="verdana,arial">
<CENTER><H1>Examenboek-enqu&ecirc;te</H1></CENTER>

<?PHP

if (!$ingelogd[0]) {
  $html =  htmlwelkomscherm();
} else {
  if (secsleft() < 0) {
    $html=gesloten();
  } else {
    if ($wijz) {
      $html = wijziging($_POST['stamnr'],$_POST['tekst1'],$_POST['tekst2'],$_POST['fotoakkoord'],$_POST['wachtwoord'],
			$_POST['lln1'],$_POST['overlln1'],
		    $_POST['lln2'],$_POST['overlln2'],
		    $_POST['lln3'],$_POST['overlln3'],
		    $_POST['lln4'],$_POST['overlln4'],
		    $_POST['lln5'],$_POST['overlln5'],
		   $_POST['doc1'],$_POST['overdoc1'],
		   $_POST['doc2'],$_POST['overdoc2'],
		   $_POST['doc3'],$_POST['overdoc3'],
		   $_POST['doc4'],$_POST['overdoc4'],
		   $_POST['doc5'],$_POST['overdoc5'],
		   $_POST['doc6'],$_POST['overdoc6'],
		   $_POST['doc7'],$_POST['overdoc7'],
		   $_POST['doc8'],$_POST['overdoc8'],
		   $_POST['doc9'],$_POST['overdoc9'],
			$_POST['doc10'],$_POST['overdoc10']);
    } else if ($opst) {
      $html = invoer($_POST['stamnr'],$_POST['wachtwoord'],$_POST['tekst1'],$_POST['tekst2'],$_POST['fotoakkoord'],
		    $_POST['lln1'],$_POST['overlln1'],
		    $_POST['lln2'],$_POST['overlln2'],
		    $_POST['lln3'],$_POST['overlln3'],
		    $_POST['lln4'],$_POST['overlln4'],
		    $_POST['lln5'],$_POST['overlln5'],
		   $_POST['doc1'],$_POST['overdoc1'],
		   $_POST['doc2'],$_POST['overdoc2'],
		   $_POST['doc3'],$_POST['overdoc3'],
		   $_POST['doc4'],$_POST['overdoc4'],
		   $_POST['doc5'],$_POST['overdoc5'],
		   $_POST['doc6'],$_POST['overdoc6'],
		   $_POST['doc7'],$_POST['overdoc7'],
		   $_POST['doc8'],$_POST['overdoc8'],
		   $_POST['doc9'],$_POST['overdoc9'],
		     $_POST['doc10'],$_POST['overdoc10']);
    } else {
      $html = htmlingelogdscherm($_POST['stamnr'],$_POST['wachtwoord']);
    }
  }
}

print $html;

?>

<!-- EINDE HOOFDVELD -->
</TD>
</TR>
</TABLE>
</BODY>
</HTML>
