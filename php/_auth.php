<?
/*******************************************************
* auth.php
* Copyright 2010, LBJWillStay.com
*******************************************************/
include_once('assets/php/util.php');

$mseg = "";

// authorization logic
if( $_GET['action'] == 'logout' )
{
   $target = urlencode( $_POST['target'] );

   if( $sessionID = $_COOKIE['sessionid'] )
   {
      $sql = "DELETE FROM sessions WHERE id = '$sessionID';";
      $res = dbQuery($sql);
      //echo "$sql<br /><br />";
   }

   // delete the cookies by setting its timeout to zero:
   setcookie( 'sessionid', '', time() - 3600 );

   if( $target )
   {
      // redirect to target URL:
      header( "Location: $target" );
   }
   else
   {
      // redirect to home page:
      header( "Location: /");
   }

   $user_id = 0;

}
elseif($_POST['login'])
{
   // method is post, validate submitted email and pass:
   $user = trim($_POST['user']);
   $md5pass = (strpos($_POST['password'], 'pcmd:')===0) ? str_replace('pcmd:','',$_POST['password']) : md5( trim($_POST['password']) );
   $target = urlencode( $_POST['target'] );

   $sql =	"SELECT user_id FROM users WHERE user = '$user' AND password = '$md5pass' ";
   $res = dbQuery($sql);
   //echo "$sql<br /><br />";

   if ( $row = dbFetchAssoc($res) )
   {
      // Match found, VALID name-pass combo:
      $user_id = $row['user_id'];
   }
   else
   {
      // No results found, INVALID name-pass combo:
      $user_id = 0;
      $mseg = 'authenication failed: invalid user or password.';
   }

}
else
{
   $user_id = 0;
}

if ( $user_id )
{
   // If VALID name-pass combo:
   $sessionID =	uniqid('');
   $sql =	"INSERT INTO sessions SET id='$sessionID', user_id='$user_id'";
   $res = dbQuery($sql);

   //set cookie
   setcookie( 'sessionid', $sessionID );
}
elseif($mseg=="") 
{
  $mseg = "user id 0";
}

echo $mseg;
exit();

?>



