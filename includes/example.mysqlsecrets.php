<?PHP

$hostname = "localhost";
$username = "user";
$password = "password";
$dbName = "DatabaseName";
MYSQL_CONNECT($hostname, $username, $password) or die ("Unable to connect to database.");
MYSQL_SELECT_DB("$dbName") or die("Unable to select database");

?>