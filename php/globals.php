<?
/**
 * @author Ryan Pastorelle
 * @version 2012-02-05
 */

// set the doc root dir
//$rootdir = $_SERVER['DOCUMENT_ROOT'];
define( 'ROOTDIR', "/Users/ryanpastorelle/Sites/howfastdoyoutype" );
define( 'ROOTURL', "http://howfastdoyoutype.com" );
define( 'DEVURL', "http://howfastdoyoutype:8888" );

// db vars
if( $_SERVER['HTTP_HOST']!='howfastdoyoutype.com' ){
    // LOCL VARS
    define( 'DBHOST', "localhost" );
    define( 'DBNAME', "howfastdoyoutype" );
    define( 'DBUSER', "root" );
    define( 'DBPASS', "rpp" );
}else{
    // LIVE VARS
    define( 'DBHOST', "pangrams.db.6259523.hostedresource.com" );
    define( 'DBNAME', "pangrams" );
    define( 'DBUSER', "pangrams" );
    define( 'DBPASS', "PANgr4m5!" );
}

// dir for logging messages
define( 'LOGDIR', "logs" );

//set user_info cookie time to 30 days
$cookie_expire = time()+60*60*24*30;
$cookie_renew = time()+60*60*24*23;

//email vars
$admin_email = "rpastorelle@yahoo.com";

//img vars
$upload_dir = "/assets/images/witness";
$upload_max_width = 450;
$thumbsize = 150; //thumbnail width & height


?>