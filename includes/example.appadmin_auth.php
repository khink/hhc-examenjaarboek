<?PHP
$beheerlogin = 'directie';
$beheerwachtwoord = 'directie';

function login_beheer($naam,$pw){
global $beheerlogin, $beheerwachtwoord;
  if ($naam==$beheerlogin and $pw==$beheerwachtwoord) {
  return true;
  } else {
    return false;
  }
}

?>
