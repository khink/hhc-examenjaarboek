<?PHP

$fotohost = "";
$lokaalfotopad = "fotos/";
$fotopad = $fotohost.$lokaalfotopad;
$maxvan = 10; /* aantal omschrijvingen die een leerling mag doen */
$maxvoor = 10; /* aantal omschrijvingen die een leerling mag hebben */
$docmaxvan = 10;

// maakt HTML-inlogschermpje
function htmlloginscherm() {
$html.="<FORM ACTION=\"$PHP_SELF\" METHOD=post>
stamnr<BR>
<INPUT NAME=stamnr TYPE=\"text\" SIZE=4 LENGTH=4><BR>
wachtwoord<BR>
<INPUT NAME=wachtwoord TYPE=password SIZE=6><BR>
<INPUT TYPE=submit NAME=loggen VALUE=\"log in\">
</FORM>";
 return $html;
}

// maakt HTML-inloggegevens + uitlogschermpje
function htmlingelogdals($stamnr) {
  $html.="<B>Ingelogd&nbsp;als:</B> $stamnr";
$html.="<FORM ACTION=\"$PHP_SELF\" METHOD=post>
<INPUT TYPE=submit NAME=loggen VALUE=\"log uit\">
</FORM>";
 return $html;
}

// bekijkt of gegeven stamnr+wachtwoord overeenkomen met wat in de db
// staat en geeft de gegevens van die persoon terug (array)
function ingelogd($stamnr,$wachtwoord) {
  if (!$stamnr) {
    return 0;
  } else {
    include("mysqlsecrets.php");
    $sql="SELECT voornaam,tussenvoegsel,achternaam,klas FROM Hleerlingen WHERE (stamnr=$stamnr AND wachtwoord=\"$wachtwoord\")";
    $result=mysql_query($sql) or die("Ongeldige query: " . mysql_error() . "<BR><TT>" . $sql ."</TT>");
    if (mysql_num_rows($result) == 1) {
      // schrijf inloggegevens weg
      $sql2 = "insert into Hingelogd set stamnr=$stamnr";
      ($result2 = mysql_query($sql2)) or die("Fout in query $sql2!");
      return(mysql_fetch_row($result));
    } else {
      return 0;
    }
  }
}

// hier het algemen scherm, de homepage
function htmlwelkomscherm() {
$html.="<H2>Leerling-login</H2>
<P>Op deze site kun je de foto controleren en de teksten invoeren waarmee je in het
eindexamenboek komt. <P>Je moet ingelogd zijn om dit te kunnen doen.</P>";
 return $html;
}

// hier het scherm, als je ingelogd bent
function htmlingelogdscherm($stamnr,$wachtwoord) {
  include("mysqlsecrets.php");
  $sql="SELECT fotoakkoord, tekst1, tekst2, tekst3 FROM Hexboekdata WHERE stamnr=$stamnr";
  $result=mysql_query($sql) or die("Ongeldige query: " . mysql_error());
  if (mysql_num_rows($result) == 0) {
    $html = "Je hebt nog geen gegevens ingevuld. \n";
    $html.=htmlgegevensinvoeren($stamnr,$wachtwoord);
  } else {
    $html = toongegevens($stamnr,$wachtwoord);
  }
  return $html;
}


// wie nog geen gegevens heeft ingevoerd krijgt dit scherm te zien
function htmlgegevensinvoeren($stamnr,$wachtwoord) {
  $html="Vul in en klik op de knop <A HREF=\"#opsturen\">onderaan de pagina</A> om deze gegevens op te sturen.\n"; 
  $html.=standaarddeelformulier($stamnr,$tekst1,$tekst2,$tekst3,1,$wachtwoord);
  $html.="<A NAME=\"opsturen\"><H3>Opsturen</H3>\n";
$html.="<INPUT TYPE=submit NAME=gegevensopsturen VALUE=\"stuur deze gegevens op\">
</FORM> ";
 return $html;
}

// functie om gegevens te veranderen
function invoer($stamnr,$wachtwoord,$tekst1,$tekst2,$tekst3,$fotoakkoord,  
$l1,$ol1,
$l2,$ol2,
$l3,$ol3,
$l4,$ol4,
$l5,$ol5,  
$l6,$ol6,  
$l7,$ol7,  
$l8,$ol8,  
$l9,$ol9,  
$l10,$ol10,  
$doc1, $odoc1, $doc2, $odoc2, $doc3, $odoc3, $doc4, $odoc4, $doc5, $odoc5,  $doc6, $odoc6, $doc7, $odoc7, $doc8, $odoc8, $doc9, $odoc9, $doc10, $odoc10) {
  include("mysqlsecrets.php");
  global $maxvan, $maxvoor, $docmaxvan;
    $ol1 = striphtml($ol1);
    $ol2 = striphtml($ol2);
    $ol3 = striphtml($ol3);
    $ol4 = striphtml($ol4);
    $ol5 = striphtml($ol5);
    $ol6 = striphtml($ol6);
    $ol7 = striphtml($ol7);
    $ol8 = striphtml($ol8);
    $ol9 = striphtml($ol9);
    $ol10 = striphtml($ol10);
    $odoc1 = striphtml($odoc1);
    $odoc2 = striphtml($odoc2);
    $odoc3 = striphtml($odoc3);
    $odoc4 = striphtml($odoc4);
    $odoc5 = striphtml($odoc5);
    $odoc6 = striphtml($odoc6);
    $odoc7 = striphtml($odoc7);
    $odoc8 = striphtml($odoc8);
    $odoc9 = striphtml($odoc9);
    $odoc10 = striphtml($odoc10);
  $leerling = array($l1, $l2, $l3, $l4, $l5, $l6, $l7, $l8, $l9, $l10);
  $omschr = array($ol1, $ol2, $ol3, $ol4, $ol5, $ol6, $ol7, $ol8, $ol9, $ol10);
  $docent = array($doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $doc7, $doc8, $doc9, $doc10);
  $docomschr = array($odoc1, $odoc2, $odoc3, $odoc4, $odoc5, $odoc6, $odoc7, $odoc8, $odoc9, $odoc10);

  $html = "";
  /* check wachtwoord */
  $sql="SELECT COUNT(*) FROM Hleerlingen WHERE (stamnr=$stamnr AND wachtwoord=\"$wachtwoord\")";
  $result=mysql_query($sql) or die("Ongeldige query: " . mysql_error());
  $array = mysql_fetch_row($result);
  if  ($array[0] ==1) {
    /* oneliner en verhaal opslaan */
    $tekst1 = striphtml($tekst1);
    $tekst2 = striphtml($tekst2);
    $tekst3 = striphtml($tekst3);
    $sql="INSERT INTO Hexboekdata (stamnr, fotoakkoord, tekst1, tekst2, tekst3) VALUES ('$stamnr','$fotoakkoord','$tekst1','$tekst2','$tekst3')";
    $result=mysql_query($sql) or die("Ongeldige query: " . mysql_error());
    /* omschrijvingen over andereleerlingen opslaan */
    for ($i = 0; $i < $maxvan; $i++) {
      if ($omschr[$i]) {
	/* kijken of leerling nog vrij is (< maxvoor bijdragen) */
	$sql3 = "SELECT COUNT(*) FROM Hllnomschr WHERE voor='$leerling[$i]'";
	$result3 = mysql_query($sql3) or die("Ongeldige query: " . mysql_error());
	$array = mysql_fetch_row($result3);
	$aantalvoor = $array[0];
	/* kijken of deze combinatie van-voor er al instaat */ 
	$sql4 = "SELECT COUNT(*) FROM Hllnomschr WHERE van='$stamnr' and voor='$leerling[$i]'";
	$result4 = mysql_query($sql4) or die("Ongeldige query: " . mysql_error());
	$array = mysql_fetch_row($result4);
	$aantalvandezevoordeze = $array[0];
	if ($aantalvoor < $maxvoor && $aantalvandezevoordeze == 0) {
	  $sql2="INSERT INTO Hllnomschr (van, voor, omschrijving) VALUES ('$stamnr','$leerling[$i]','$omschr[$i]')";
	  $result2 = mysql_query($sql2) or die("Ongeldige query: " . mysql_error());
	}
      }
    }
    /* omschrijvingen docenten opslaan */
    for ($i = 0; $i < $docmaxvan; $i++) {
      if ($docomschr[$i]) {
	/* kijken of deze combinatie van-voor er al instaat */ 
	$sql4 = "SELECT COUNT(*) FROM Hdocomschr WHERE van='$stamnr' and voor='$docent[$i]'";
	$result4 = mysql_query($sql4) or die("Ongeldige query: " . mysql_error());
	$array = mysql_fetch_row($result4);
	$aantalvandezevoordeze = $array[0];
	if ($aantalvandezevoordeze == 0) {
	  $sql2="INSERT INTO Hdocomschr (van, voor, omschrijving) VALUES ('$stamnr','$docent[$i]','$docomschr[$i]')";
	  $result2 = mysql_query($sql2) or die("Ongeldige query: " . mysql_error());
	}
      }
    }
    if ($result) {
      $html .= invoergelukt();
    } else {
      $html .= invoermislukt();
    }
  } else {
    $html .= "foutje!";
  }
  return $html;
}

function toongegevens($stamnr,$wachtwoord){
  $html.="Als je het zo wilt laten, dan kun je uitloggen.<BR>\n";
  $html.="Als je iets wilt aanpassen, verander het dan en klik op de knop <A HREF=\"#opsturen\">onderaan</A>.<BR>\n"; 
  $html.="Dit heb je ingevoerd:<BR>";
  include("mysqlsecrets.php");
  $sql="SELECT tekst1,tekst2,tekst3,fotoakkoord FROM Hexboekdata WHERE stamnr='$stamnr'";
  $result=mysql_query($sql) or die("Ongeldige query: " . mysql_error());
  $waarden=mysql_fetch_row($result);
  $tekst1=$waarden[0];
  $tekst2=$waarden[1];
  $tekst3=$waarden[2];
  $fotoakkoord=$waarden[3];
  $html.=standaarddeelformulier($stamnr,$tekst1,$tekst2,$tekst3,$fotoakkoord,$wachtwoord);
$html.="<A NAME=\"opsturen\">
<H3>Wijzigen</H3>
<INPUT TYPE=submit NAME=gegevenswijzigen VALUE=\"Stuur de bovenstaande gewijzigde gegevens op\">
</FORM> ";
 return $html;

}

// hier het standaarddeel van ieder formulier
function standaarddeelformulier($stamnr,$tekst1,$tekst2,$tekst3,$fotoakkoord,$wachtwoord){
  global $fotopad;
  $fotobestand_instroom = $fotopad.'instroom/'.$stamnr.".jpg";
  $fotobestand_nu = $fotopad.'nu/'.$stamnr.".jpg";
  $sql = "SELECT voornaam, tussenvoegsel, achternaam, klas, woonplaats, loopbaan FROM Hleerlingen WHERE stamnr='$stamnr'";
  $result = mysql_query($sql) or die("Ongeldige query: " . mysql_error());
  $waarden = mysql_fetch_row($result);
  $voornaam = $waarden[0];
  $tussenvoegsel = $waarden[1];
  $achternaam = $waarden[2];
  $klas = $waarden[3];
  $woonplaats = $waarden[4];
  $loopbaan = $waarden[5];
  $checked0=$checked1="";
  if ($fotoakkoord) {
    $checked1 = "CHECKED";
  } else {
    $checked0 = "CHECKED";
  }
  $llnomschrform = llnomschrform($stamnr);
  $docomschrform = docomschrform($stamnr);
$html = "<FORM ACTION=\"$PHP_SELF\" METHOD=POST>
<!--H3>Gegevens</H3>
<P>
Naam: $voornaam $tussenvoegsel $achternaam<BR>
Klas: $klas<BR>
Woonplaats: $woonplaats<BR>
Schoolloopbaan: $loopbaan<BR>
Als deze gegevens niet kloppen, mail dat dan aan <A HREF=\"mailto:m.vanduin@hogeland.nl\">m.vanduin@hogeland.nl</A>.
</P-->
<h3>Een regel over je foto&#39;s</h3>

<img src=\"$fotobestand_instroom\" height=\"280\">
<img src=\"$fotobestand_nu\">


<p> Omschrijf  hier  wat je eigen brugklasfoto en je huidige foto bij je
oproepen en vertel ook wat je na het HHC gaat doen en wat je later wilt worden
in maximaal 200 karakters.</p>

<INPUT TYPE=text SIZE=80 MAXLENGTH=200 NAME=tekst3 VALUE=\"$tekst3\">

$llnomschrform
$docomschrform

<H3>Vul de omschrijving aan</H3> 

  Hier kun je invullen wat je van onderstaande items
  vindt (met de reden er eventueel achter). Je hoeft niet alles in te
  vullen en je kunt ook een eigen item toevoegen. (maximaal 200
  karakters)

<UL>
<li>Het leukste vak ...</li>
<li>De beste spijbeltruc ...</li>
<li>De beste spiektruc ...</li>
<li>Het vervelendste, saaiste etc. van school is...</li>
<li>Het fijnste, leukste, gekste etc. van school is...</li>
<li>Ik was altijd stiekem verliefd op...</li>
<li>Wat ik zal missen van deze school is ...</li>
<li>....................</li>
</UL> 
<INPUT TYPE=text SIZE=80 MAXLENGTH=200 NAME=tekst1 VALUE=\"$tekst1\">

<h3>Anekdotes</h3>

<p>Iedereen heeft zo zijn fijne, grappige etc. herinneringen aan
school. Schrijf hier jouw tekst(je) over &eacute;en van de
onderstaande items:</p>
<ul>
<li>eerste schooldag</li>
<li>leukste vak</li>
<li>fijnste leraar</li>
<li>de dagelijkse tocht naar school </li>
<li>te laat komen</li>
<li>spijbelen</li>
<li>spieken</li>
<li>gedoe in de klas</li>
<li>de eerste keer eruit gestuurd</li>
<li>mooiste verliefdheden</li>
<li>mooiste vriendschappen</li>
<li>stiekem roken</li>
<li>het meest bijzondere leslokaal</li>
<li>geheime plekjes in het gebouw</li>
<li>onderweg naar school</li>
<li>met de bus naar het praktijkgebouw</li>
<li>daltonuur</li>
<li>zelfstudieuur</li>
<li>schoolreis</li>
<li>excursies</li>
<li>sporttoernooi</li>
<li>discoavond</li>
<li>Advendo</li>
<li>creatieve werkweek</li>
<li>muziekavond</li>
<li>alles anders dan anders dagen</li>
<li>landendag</li>
<li>talentenjacht</li>
<li>examenstress</li>
</ul>

<p>Eventuele bijbehorende foto&#39;s met naam en klas en onderwerp mailen naar:
<a href=\"mailto:t.nanninga@hogeland.nl\">t.nanninga@hogeland.nl</a>.</p>

<TEXTAREA ROWS=8 COLS=60 NAME=tekst2>
$tekst2
</TEXTAREA>

<h3>Pasfoto</h3>

<p>Er komt een brugklaspasfoto  van jou in het examenjaarboek en de pasfoto van
het afgelopen jaar. Beide foto&#39;s staan op de site.</p>

<p>Als je je huidige pasfoto niet geschikt vindt om in het examenjaarboek te
komen, kruis dit dan aan en mail de nieuwe pasfoto met naam en klas naar
<a href=\"mailto:t.nanninga@hogeland.nl\">t.nanninga@hogeland.nl</a>.</p>

<p>Mogen deze foto&#39;s worden gebruikt?<br />
<input $checked1 type=radio name=fotoakkoord value=1>Ja<br />
<input $checked0 type=radio name=fotoakkoord value=0>Nee, ik lever een andere foto in.   
</p>

<INPUT TYPE=hidden NAME=stamnr VALUE=$stamnr>
<INPUT TYPE=hidden NAME=wachtwoord VALUE=$wachtwoord>";
 return $html;
}

function wijziging($stamnr,$tekst1,$tekst2,$tekst3,$fotoakkoord,$wachtwoord,  
$l1,$ol1,
$l2,$ol2,
$l3,$ol3,
$l4,$ol4,
$l5,$ol5,  
$l6,$ol6,  
$l7,$ol7,  
$l8,$ol8,  
$l9,$ol9,  
$l10,$ol10,  
$doc1, $odoc1, $doc2, $odoc2, $doc3, $odoc3, $doc4, $odoc4, $doc5, $odoc5,  $doc6, $odoc6, $doc7, $odoc7, $doc8, $odoc8, $doc9, $odoc9, $doc10, $odoc10) {
  include("mysqlsecrets.php");
  global $maxvan, $maxvoor, $docmaxvan;
    $ol1 = striphtml($ol1);
    $ol2 = striphtml($ol2);
    $ol3 = striphtml($ol3);
    $ol4 = striphtml($ol4);
    $ol5 = striphtml($ol5);
    $ol6 = striphtml($ol6);
    $ol7 = striphtml($ol7);
    $ol8 = striphtml($ol8);
    $ol9 = striphtml($ol9);
    $ol10 = striphtml($ol10);
    $odoc1 = striphtml($odoc1);
    $odoc2 = striphtml($odoc2);
    $odoc3 = striphtml($odoc3);
    $odoc4 = striphtml($odoc4);
    $odoc5 = striphtml($odoc5);
    $odoc6 = striphtml($odoc6);
    $odoc7 = striphtml($odoc7);
    $odoc8 = striphtml($odoc8);
    $odoc9 = striphtml($odoc9);
    $odoc10 = striphtml($odoc10);
  $leerling = array($l1, $l2, $l3, $l4, $l5, $l6, $l7, $l8, $l9, $l10);
  $omschr = array($ol1, $ol2, $ol3, $ol4, $ol5, $ol6, $ol7, $ol8, $ol9, $ol10);
  $docent = array($doc1, $doc2, $doc3, $doc4, $doc5, $doc6, $doc7, $doc8, $doc9, $doc10);
  $docomschr = array($odoc1, $odoc2, $odoc3, $odoc4, $odoc5, $odoc6, $odoc7, $odoc8, $odoc9, $odoc10);

  if  (1) {
    $tekst1 = striphtml($tekst1);
    $tekst2 = striphtml($tekst2);
    $tekst3 = striphtml($tekst3);
    $sql="UPDATE Hexboekdata SET fotoakkoord='$fotoakkoord' , tekst1=\"$tekst1\" , tekst2=\"$tekst2\" , tekst3=\"$tekst3\" WHERE stamnr='$stamnr'";
    $result=mysql_query($sql) or die("Ongeldige query: " . mysql_error() . "<BR><TT>" . $sql ."</TT>");
    $html.=$result[0];
    /* omschrijvingen over andereleerlingen opslaan */
    for ($i = 0; $i < $maxvan; $i++) {
      if ($omschr[$i]) {
	/* kijken of leerling nog vrij is (< maxvoor bijdragen) */
	$sql3 = "SELECT COUNT(*) FROM Hllnomschr WHERE voor='$leerling[$i]'";
	$result3 = mysql_query($sql3) or die("Ongeldige query: " . mysql_error());
	$array = mysql_fetch_row($result3);
	$aantalvoor = $array[0];
	/* kijken of deze combinatie van-voor er al instaat */ 
	$sql4 = "SELECT COUNT(*) FROM Hllnomschr WHERE van='$stamnr' and voor='$leerling[$i]'";
	$result4 = mysql_query($sql4) or die("Ongeldige query: " . mysql_error());
	$array = mysql_fetch_row($result4);
	$aantalvandezevoordeze = $array[0];
	if ($aantalvoor < $maxvoor && $aantalvandezevoordeze == 0) {
	  $sql2="INSERT INTO Hllnomschr (van, voor, omschrijving) VALUES ('$stamnr','$leerling[$i]','$omschr[$i]')";
	  $result2 = mysql_query($sql2) or die("Ongeldige query: " . mysql_error());
	}
      }
    }
    /* omschrijvingen docenten opslaan */
    for ($i = 0; $i < $docmaxvan; $i++) {
      if ($docomschr[$i]) {
	/* kijken of deze combinatie van-voor er al instaat */ 
	$sql4 = "SELECT COUNT(*) FROM Hdocomschr WHERE van='$stamnr' and voor='$docent[$i]'";
	$result4 = mysql_query($sql4) or die("Ongeldige query: " . mysql_error());
	$array = mysql_fetch_row($result4);
	$aantalvandezevoordeze = $array[0];
	if ($aantalvandezevoordeze == 0) {
	  $sql2="INSERT INTO Hdocomschr (van, voor, omschrijving) VALUES ('$stamnr','$docent[$i]','$docomschr[$i]')";
	  $result2 = mysql_query($sql2) or die("Ongeldige query: " . mysql_error());
	}
      }
    }
    if ($result) {
      //      $html .= invoergelukt();
      $html.= toongegevens($stamnr,$wachtwoord);
    } else {
      $html .= invoermislukt();
    }
  } else {
    $html = "foutje!";
  }
  return $html;
}

function invoergelukt() {
  $html="Het invoeren van je gegevens is gelukt.<P> 
Log uit om af te sluiten. Je kunt opnieuw inloggen als je iets wilt aanpassen. \n";
  return $html;
}

function invoermislukt(){
  $html="De invoer is mislukt!<BR>";
  return $html;
}

function logger() {
  include("mysqlsecrets.php");
  $host = $_SERVER['REMOTE_ADDR'];
  $post_info = join("|",$_POST);
  $rest = join("|",$_REQUEST);
  $sql="INSERT INTO Hlog SET postdata=\"$post_info\", ipnummer='$host', rest=\"$rest\", stamnr='stamnr'";
  $result = mysql_query($sql) or die("Ongeldige query: " . mysql_error() . "<BR><TT>" . $sql ."</TT>");
}

function authenticate(){
  header( 'WWW-Authenticate: Basic realm="Examenboek Hogeland College"' );
  header( 'HTTP/1.0 401 Unauthorized' );
  echo 'Authorization required.';
  exit;
}

function beheerscherm() {
  global $fotopad, $fotohost, $lokaalfotopad;
  include("mysqlsecrets.php");
  $sql = "select stamnr, voornaam, tussenvoegsel, achternaam, klas, locatie from Hleerlingen order by locatie, klas, achternaam, voornaam";
  ($result = mysql_query($sql)) || die("Fout in query: $sql");
  $html.= "<FORM><INPUT TYPE=submit NAME=overzicht VALUE=\"Alle leerlingen op &eacute;&eacute;n pagina\">\n";
  $html.= "<INPUT TYPE=submit NAME=docoverzicht VALUE=\"Alle docenten op &eacute;&eacute;n pagina\"></FORM><BR> \n";
  $html.="<TABLE><TR BGCOLOR=#B0B000>";
  $html.="<TH>klas</TH>";
  $html.="<TH>voornaam</TH>";
  $html.="<TH>tv</TH>";
  $html.="<TH>achternaam</TH>";
  $html.="<TH>laatst<BR>ingelogd</TH>";
  $html.="<TH>verplichte<BR>velden<BR>ingevuld</TH>";
  $html.="<TH>foto<BR>akkoord</TH>";
  $html.="<TH>opgestuurde<BR>tekst</TH>";
  $html.="</TR>\n";


  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $stamnr = $row[0];
    $html.="<!-- $stamnr -->";
    $html.="<TR>";
    $html.="<TD>".$row[4]."</TD>";
    $html.="<TD>".$row[1]."</TD>";
    $html.="<TD>".$row[2]."</TD>";
    $html.="<TD>".$row[3]."</TD>";
    // wanneer is deze persoon voor het laatst ingelogd?
    $sql3 = "select tijd from Hingelogd where stamnr=$stamnr order by tijd desc"; 
    ($result3 = mysql_query($sql3)) or die("Fout in query $sql3!");
    if ($tijdresultaat = mysql_fetch_array($result3)) {
      $tijd = formatdate($tijdresultaat[0]);
    } else {
      $tijd="nooit";
    }
    $html.="<TD><FONT SIZE=-2>".$tijd."</FONT></TD>";

    $sql2 = "select tekst1, tekst2, fotoakkoord from Hexboekdata where stamnr=$stamnr";
    ($result2 = mysql_query($sql2)) || die("Fout in query: $sql2");
      $data = mysql_fetch_array($result2, MYSQL_NUM);
      $oneliner = $data[0]; 
      $verhalen = $data[1]; 
      $fotoakkoord = $data[2];

      /* check op gegevens "over" */
      $sql3 = "SELECT COUNT(*) FROM Hllnomschr WHERE voor='$stamnr'";
      $result3 = mysql_query($sql3);
      $array = mysql_fetch_row($result3);
      $aantalover = $array[0];

      /* check op gegevens "van" */
      $sql4 = "SELECT COUNT(*) FROM Hllnomschr WHERE van='$stamnr'";
      $result4 = mysql_query($sql4);
      $array = mysql_fetch_row($result4);
      $aantalvan = $array[0];

    // gegevens ingevuld?
    $html.="<TD ALIGN=center><B><FONT COLOR=";
    if ($aantalvan > 0) {
      $html.="green>V";
    } else {
      $html.="red>X";
    }
    $html.="</FONT></B></TD>";
    // foto akkoord?
    $html.="<TD ALIGN=center>";
    //kijk of foto bestaat
    $fotobestand_nu = $lokaalfotopad.'nu/'.$stamnr.".jpg";
    $fotobestand_instroom = $lokaalfotopad.'instroom/'.$stamnr.".jpg";
    if ( ! file_exists($fotobestand_instroom) ) {
        $html.="<a href=\"\" title=\"instroomfoto ontbreekt\">i</a> ";
    }
    if ( ! file_exists($fotobestand_nu) ) {
        $html.="<a href=\"\" title=\"actuele foto ontbreekt\">n</a> ";
    }

    if (($tijd=="nooit") or !($oneliner or $wistudat or $verhalen)) {
      $html.="nvt";
    } else {
      if ($fotoakkoord==true) {
	$html.="ja";
      } else { 
	$html.="nee";
      }
    }
    $html.="</TD>";

    //linkje naar totaalpagina
    $html.="<TD>";
    if ($oneliner || $verhalen || $aantalover > 0) {
      $self = $_SERVER['PHP_SELF'];
      $html.="<A HREF=\"$self?nr=$stamnr\">klik om te lezen</A>";
    } 
    $html.="</TD>";

    $html.="</TR>\n";
  }
  $html.="</TABLE>\n";
  return $html;
}

function formatdate($stamp) {
/*
			    if (ereg ("([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})", $stamp, $regs)) {
      $date="$regs[3]-$regs[2]-$regs[1] $regs[4]:$regs[5]:$regs[6]";
    } else {
      $date="Invalid date format: $stamp<BR>\n";
    }
*/
  $date=$stamp;
    return $date;
}

function docentscherm($nr) {
  $html = docentomschrijvingen($nr);
  return $html;
}

function leerlingscherm($stamnr) {
  global $fotopad;
  $fotobestand_nu = $fotopad.'nu/'.$stamnr.".jpg";
  $fotobestand_instroom = $fotopad.'instroom/'.$stamnr.".jpg";
  include("mysqlsecrets.php");
  $sql="SELECT tekst1,tekst2,tekst3,fotoakkoord FROM Hexboekdata WHERE stamnr='$stamnr'";
  $result=mysql_query($sql) or die("Ongeldige query: " . mysql_error());
  $sql2 = "select voornaam, tussenvoegsel, achternaam, klas, locatie from Hleerlingen where stamnr=$stamnr";
  ($result2 = mysql_query($sql2)) || die("Fout in query: $sql");
  $waarden=mysql_fetch_row($result);
  $tekst1=$waarden[0];
  $tekst2=$waarden[1];
  $tekst3=$waarden[2];
  //regeleindes in verhaal omzetten
  $tekst2 = preg_replace("'\n'", "<BR>\n", $tekst2);
  $fotoakkoord=$waarden[3];
  $leerlinggegevens =mysql_fetch_row($result2); 
  $voornaam = $leerlinggegevens[0];
  $tv = $leerlinggegevens[1];
  $achternaam = $leerlinggegevens[2];
  $klas = $leerlinggegevens[3];
  $naam = $voornaam." ".$tv." ".$achternaam." (".$klas.")"; 
  $html.= "<FORM><INPUT TYPE=submit NAME=doeterniettoe VALUE=\"Terug naar leerlingenoverzicht\"></FORM><BR> \n";
  $html.="<H2>$naam</H2>\n";
  $html.="<TABLE WIDTH=100%><TR><TD>";
  $html.="<H3>Vul de omschrijving aan:</H3>\n";
  $html.=$tekst1;
  $html.="<H3>Anekdote:</H3>\n";
  $html.=$tekst2;
  $html.="<H3>Over foto&#39;s:</H3>\n";
  $html.=$tekst3;
  $html.="<H3>Medeleerlingen zeggen:</H3>\n";
  $html.=leerlingomschrijvingen($stamnr);
  $html.="<H3>$voornaam over anderen:</H3>";
  $html.=omschrijvingenvanleerling($stamnr);
  $html.="</TD><TD VALIGN=top ALIGN=right>";
  $html.="<IMG SRC=\"$fotobestand_instroom\" height=\"280\"><BR>";
  $html.="<IMG SRC=\"$fotobestand_nu\"><BR>";
  $html.="<FONT SIZE=-1><B>Deze foto mag ";
  if (($fotoakkoord != true) and ($fotoakkoord != NULL)) {
    $html.= "<FONT COLOR=red>niet</FONT> ";
  }
  $html.="worden gebruikt in het boekje.</B></FONT>";
  $html.="</TD></TR></TABLE>";
 return $html;

}

function striphtml($tekst) {
  $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
		   "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
		   "'<'",
		   "'<'",
		   "'&'");

  $replace = array ("",
		    "",
		    "&lt;",
		    "&gt;",
		    "&amp;");
  $tekst = preg_replace($search, $replace, $tekst);

  return $tekst;
}

function einddatum(){
  $einddatum="12-03-2013 23:59:59";
  $eind=strtotime($einddatum);
  return $eind;
}

function startdatum(){
  $startdatum="1-apr-2001 11:11:11";
  $start=strtotime($startdatum);
  return $start;
}

function secsleft(){
  $eind=einddatum();
  $nu=time();
  $verschil = $eind - $nu;
  return $verschil;
}

function afteller() {
  $over = secsleft();
  $tijd = secstodays($over);
  if ($over > 0) {
    $html="Nog $tijd tot de sluiting!\n"; 
  } else {
    $html="De deadline is verstreken.\n";
  }
  return $html;
}

function secstodays($secs) {
  $days = floor($secs/(24*3600));
  $hours = floor(($secs - (24*3600*$days))/3600); 
  $mins = floor(($secs - (24*3600*$days) - (3600*$hours))/60); 
  // $days = $secs;
  $text = "";
  if ($days > 0) {
    $text = "$days dagen, ";
  }
  $text .= "$hours uren en $mins minuten";
  return $text;
}

function gesloten() {
  $html= "Het is niet meer mogelijk je gegevens in te vullen.\n";
  return $html;
}

function allespagina() {
  include("mysqlsecrets.php");
  $sql = "select stamnr from Hleerlingen order by locatie, klas, achternaam";
  ($result = mysql_query($sql)) || die("Fout in query: $sql");
  $html= "<FORM><INPUT TYPE=submit NAME=doeterniettoe VALUE=\"Terug naar leerlingenoverzicht\"></FORM><BR> \n";
  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $nr = $row[0];
    $html.=leerlingscherm($nr);
    $html.="<HR>";
  }
  return $html;
}

function docallespagina() {
  include("mysqlsecrets.php");
  $sql = "select nr, CONCAT(voorvoegsel, ' ', naam, ' (', locatie, ')') from Hpersoneel order by locatie, achternaam, voorvoegsel";
  ($result = mysql_query($sql)) || die("Fout in query: $sql");
  $html.= "<FORM><INPUT TYPE=submit NAME=doeterniettoe VALUE=\"Terug naar leerlingenoverzicht\"></FORM><BR> \n";
  $aantal = mysql_num_rows($result);
  $kop = "<TABLE WIDTH=100%><TR VALIGN=\"top\"><TD>\n";
  $aantalperkolom=67;
  $i=0;
  while ($row = mysql_fetch_row($result)) {
    $i++;
    $nr = $row[0];
    $naam = $row[1];
    if ($i % $aantalperkolom == 0) {
      $kop .= "</TD><TD>";
    }
    $kop.="<A HREF=\"#$nr\">$naam</A><BR>";
    $pagina.="<A NAME=$nr><H3>$naam</H3>\n";
    $pagina.=docentscherm($nr);
    $pagina.="<HR>";
  }
  $kop .= "</TD></TR></TABLE>\n";
  $html = $html.$kop.$pagina;
  return $html;
}

function llnomschrform($stamnr) {
  include("mysqlsecrets.php");
  global $maxvan, $maxvoor;
  $html = "
<h3>Een regel over een medeleerling</h3>

<p>Omschrijf hier &eacute;&eacute;n of maximaal 10 medeleerlingen in maximaal
200 karakters.  Natuurlijk kies je hierbij een positieve insteek.</p>

<p>Wie het eerst komt, wie het eerst maalt. Als bij een leerling 10 keer een
zin is ingevuld, gaat de invulmogelijkheid automatisch op slot. Het is niet
mogelijk elkaars invullingen te zien. Dit waarborgt de privacy.</p>
 
<p>Het is niet mogelijk deze opmerking(en) te herzien, denk er dus goed over
na!</p>";

  /* kijken hoeveel omschrijvingen leerling al heeft ingestuurd */
  $sql1 = "SELECT COUNT(*) FROM Hllnomschr WHERE van=$stamnr";
  $result1 = mysql_query($sql1);
  $array = mysql_fetch_row($result1);
  $aantal = $array[0];
  $html.="Je hebt $aantal van $maxvan omschrijvingen ingevuld.<BR>\n";

  for ($i = $aantal + 1; $i <= $maxvan ; $i++) {
    /* leerlingen selecteren die nog geen X (maxvoor) keer omschreven zijn, niet de leerling zichzelf laten omschrijven */
    $html.="<SELECT NAME=\"lln$i\">";
    $sql2 = "SELECT stamnr, CONCAT(voornaam,' ',tussenvoegsel,' ',achternaam,' ',klas) FROM Hleerlingen 
WHERE ((SELECT COUNT(*) FROM Hllnomschr WHERE voor=Hleerlingen.stamnr) < $maxvoor 
AND stamnr<>$stamnr
AND (SELECT COUNT(*) FROM Hllnomschr WHERE voor=Hleerlingen.stamnr AND van=$stamnr) < 1) 
ORDER BY locatie, klas, voornaam";
    $result2 = mysql_query($sql2);
    while ($array = mysql_fetch_row($result2)) {
      $html.= "<OPTION VALUE=$array[0]>$array[1]\n";
    }
    $html.="</SELECT>\n<INPUT TYPE=text SIZE=80 MAXLENGTH=200 NAME=\"overlln$i\" VALUE=\"$overleerling\"><BR>\n";
  }

  return $html;

}

function docomschrform($stamnr) {
  include("mysqlsecrets.php");
  global $docmaxvan;
  $html = "
<h3>Een regel over een docent</h3>

<p>Omschrijf hier &eacute;&eacute;n of maximaal 10 favoriete docenten of ander
personeelsleden (directie, conci&euml;rges etc.)in &eacute;&eacute;n zin
(maximaal 200 karakters). Het is niet mogelijk elkaars invullingen te zien. Dit
waarborgt de &quot;privacy&quot;.</p>";

  /* kijken hoeveel omschrijvingen leerling al heeft ingestuurd */
  $sql1 = "SELECT COUNT(*) FROM Hdocomschr WHERE van=$stamnr";
  $result1 = mysql_query($sql1);
  $array = mysql_fetch_row($result1);
  $aantal = $array[0];
  $html.="Je hebt $aantal van $docmaxvan omschrijvingen ingevuld.<BR>\n";

  for ($i = $aantal + 1; $i <= $docmaxvan ; $i++) {
    /* docenten selecteren die nog geen X keer omschreven zijn */
    $html.="<SELECT NAME=\"doc$i\">";
    $sql2 = "SELECT nr, CONCAT(voorvoegsel,' ',naam,' (',locatie,')') FROM Hpersoneel 
WHERE (SELECT COUNT(*) FROM Hdocomschr WHERE van=$stamnr AND voor=Hpersoneel.nr) < 1
ORDER BY locatie, achternaam, naam"; 
    /* evt. aanvulling sql2: 
    WHERE (SELECT COUNT(*) FROM Hdocomschr WHERE voor=Hpersoneel.nr) < $docmaxvoor"; */
    $result2 = mysql_query($sql2);
    while ($array = mysql_fetch_row($result2)) {
      $html.= "<OPTION VALUE=$array[0]>$array[1]\n";
    }
    $html.="</SELECT>\n<INPUT TYPE=text SIZE=80 MAXLENGTH=200 NAME=\"overdoc$i\" VALUE=\"$overdocent\"><BR>\n";
  }

  $html.="<FONT SIZE=-1>Het is niet mogelijk elkaars invullingen te
  zien. Dit waarborgt de privacy.</FONT><BR>";
  return $html;

}

function leerlingomschrijvingen($stamnr) {
  include("mysqlsecrets.php");
  $sql = "SELECT CONCAT(M1.voornaam,' ',M1.tussenvoegsel,' ',M1.achternaam,' (',M1.klas,')'), omschrijving, van FROM Hleerlingen AS M1 LEFT JOIN Hllnomschr AS M2 ON M1.stamnr=M2.van WHERE voor='$stamnr'";
  /* simpele versie die alleen stamnr geeft: */
  // $sql = "SELECT van, omschrijving FROM Hllnomschr WHERE voor='$stamnr'";
  $result = mysql_query($sql);
  while ($array = mysql_fetch_row($result)) {
    $html.="<I><a href=\"?nr=$array[2]\">$array[0]</a> zegt:</I><BR>$array[1]\n<BR>";
  }
  return $html;
}

function docentomschrijvingen($nr) {
  include("mysqlsecrets.php");

  $sql = "SELECT CONCAT(M1.voornaam,' ',M1.tussenvoegsel,' ',M1.achternaam,' (',M1.klas,')'), omschrijving, van FROM Hleerlingen AS M1 LEFT JOIN Hdocomschr AS M2 ON M1.stamnr=M2.van WHERE voor='$nr'";
  //$sql = "SELECT van, omschrijving FROM Hdocomschr WHERE voor='$nr'";
  $result = mysql_query($sql);
  while ($array = mysql_fetch_row($result)) {
    $html.="<I><a href=\"?nr=$array[2]\">$array[0]</a> zegt:</I><BR>$array[1]\n<BR>";
  }
  return $html;
}

function omschrijvingenvanleerling($stamnr) {
  include("mysqlsecrets.php");

  $sql1 = "SELECT CONCAT(T1.voornaam, ' ', T1.tussenvoegsel, ' ', T1.achternaam, ' (', T1.klas, ')'), omschrijving, stamnr FROM Hleerlingen AS T1 LEFT JOIN Hllnomschr AS T2 ON T1.stamnr=T2.voor WHERE van='$stamnr'";
  $result1 = mysql_query($sql1);
  while ($array = mysql_fetch_row($result1)) {
    $html.="<I>over <a href=\"?nr=$array[2]\">$array[0]</a>:</I><BR>$array[1]\n<BR>";
  }

  $sql2 = "SELECT CONCAT(T1.voorvoegsel, '  ',T1.naam, '  (',T1.locatie, ')'), omschrijving, voor FROM Hpersoneel as T1 LEFT JOIN Hdocomschr AS T2 ON T1.nr=T2.voor WHERE van='$stamnr'"; 
  $result2 = mysql_query($sql2);
  while ($array = mysql_fetch_row($result2)) {
    $html.="<I>over <a href=\"?docoverzicht=ja#$array[2]\">$array[0]</a>:</I><BR>$array[1]\n<BR>";
  }

  return $html;
}

?>
