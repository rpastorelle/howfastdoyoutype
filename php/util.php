<?
/*******************************************************
* util.php
* Copyright 2012 @rpastorelle
*******************************************************/



/*******************************************************
* Log message
* Arguments: msg, logfile
********************************************************/
function logMsg($msg, $logfile='sitelog.log')
{
   global $logdir;

   $logfile = $logdir."/".$logfile;
   $date_time = date("Y-m-d G:i:s");

   //Get IP address
   $ip = getenv ("REMOTE_ADDR");

   //Build message
   $msg = $date_time." - ".$ip.": ".$msg."\n";

   //Write the log
   $fp = fopen($logfile, "a");
   fwrite($fp, $msg);
   fclose($fp);
}

/**
 * do not chache page
 *
 */
function do_not_cache()
{
  header("Cache-Control: no-cache, must-revalidate, no-store"); // HTTP/1.1
  header("Pragma: no-cache"); // HTTP/1.0
}



// HFDYT Utils:

/**
 * Get phrase:
 */
function getPhrase( $idnum=null ){
    
    // default info:
    $phraseInfo = array();
    $phraseInfo['id'] = 1;
    $phraseInfo['phrase'] = 'The quick brown fox jumps over the lazy dog.';
    
    // grab from db:
    $qEnd = $idnum ? "WHERE `id`='{$idnum}'" : "ORDER BY RAND()";
    $query = "SELECT * FROM `phrases` {$qEnd} LIMIT 0,1";
    $resrc = dbQuery( $query );
    $numRows = dbNumRows( $resrc );
    if( $numRows ){
        $phraseInfo = dbFetchAssoc( $resrc );
    }
    return $phraseInfo;
}


/**
 * Record the stats
 * @param int $phraseId
 * @param int $userId
 * @param int $ms
 * @param float $wpm
 * @param int $errors
 * @param int $score
 */
function recordStats( $phraseId, $userId, $ms, $wpm, $errors, $score ){
    
    $time = time();
    $query = "INSERT INTO `stats` SET `phrase_id`='{$phraseId}', `user_id`='{$userId}', 
    					  `milliseconds`='{$ms}', `wpm`='{$wpm}', `errors`='{$errors}', `score`='{$score}', `time`='{$time}' ";
    $resrc = dbQuery( $query );
    return dbInsertId();
    
}















function get_age($dob)
{
  list($year,$month,$day) = explode("-",$dob);
  $year_diff = date("Y") - $year;
  $month_diff = date("m") - $month;
  $day_diff = date("d") - $day;
  if ($month_diff < 0) $year_diff--;
  elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
  return $year_diff;
}

function get_combo_values($combo_name, $where="")
{
  $combo_values = array();
  $query =	"SELECT combo_name,value,display FROM combo_values WHERE combo_name='$combo_name'";
  $query .= ($where<>"") ? " AND $where" : "";
  $query .= " ORDER BY display ASC";
  $query_result =	dbQuery($query);

  while ($row=dbFetchAssoc($query_result))
  {
    $combo_values[] = $row;
  }

  if($_GET['pcmd']=='combo_values') {echo"<pre>";print_r($combo_values);echo"</pre>";}
  return $combo_values;
}

function create_pagename($title)
{
  $title = str_replace(' ','-',strtolower($title));
  $pagename_orig = clean_alphanumeric_plus($title);
  //CHECK TO SEE IF NAME EXISTS
  $iteration = 0;
  do {
  	$pagename = ($iteration>0) ? $pagename_orig.'-'.$iteration : $pagename_orig;
  	$sql = "SELECT pagename FROM testimony WHERE pagename='$pagename'";
  	$res = dbQuery($sql);
  	$num = dbNumRows($res);
  	$iteration++;
  	//IF page-name exists try page-name1, etc
  } while($num>0);
  
  return $pagename;
}

function create_photoname($title)
{
  $title = str_replace(' ','-',strtolower($title));
  $photoname_orig = clean_alphanumeric_plus($title);
  //CHECK TO SEE IF NAME EXISTS
  $iteration = 0;
  do {
  	$photoname = ($iteration>0) ? $photoname_orig.'-'.$iteration : $photoname_orig;
  	$sql = "SELECT photoname FROM witness WHERE photoname='$photoname'";
  	$res = dbQuery($sql);
  	$num = dbNumRows($res);
  	$iteration++;
  	//IF page-name exists try page-name1, etc
  } while($num>0);
  
  return $photoname;
}

function create_videoname($title)
{
  $title = str_replace(' ','-',strtolower($title));
  $videoname_orig = clean_alphanumeric_plus($title);
  //CHECK TO SEE IF NAME EXISTS
  $iteration = 0;
  do {
  	$videoname = ($iteration>0) ? $videoname_orig.'-'.$iteration : $videoname_orig;
  	$sql = "SELECT videoname FROM video WHERE videoname='$videoname'";
  	$res = dbQuery($sql);
  	$num = dbNumRows($res);
  	$iteration++;
  	//IF page-name exists try page-name1, etc
  } while($num>0);
  
  return $videoname;
}

function set_jersey($color='winegold'){
  global $cookie_expire;
  setcookie( 'cavs', $color, $cookie_expire );
}
function get_jersey(){
  $time = time();
  $default = ($time%2==0) ? 'blueorange' : 'winegold';
  return ($cavs=$_COOKIE['cavs']) ? $cavs : $default;
}
function update_views($table,$id){
  $sql = "UPDATE `$table` SET views=(views+1) WHERE `id`='$id'";
  $res = dbQuery($sql);  
}
function update_downloads($table,$id){
  $sql = "UPDATE `$table` SET downloads=(downloads+1) WHERE `id`='$id'";
  $res = dbQuery($sql);  
}
function update_status($table,$status,$id){
  $sql = "UPDATE `$table` SET status='$status' WHERE `id`='$id'";
  $res = dbQuery($sql);  
}

/**
 * prep for database entry
 * removes semi-colons and escapes single-quotes
 */
function escapeData($data)
{
  $data = str_replace(';', '', $data);
  $data = addcslashes($data, "'");
  return $data;
}

function valid_email($email)
{
  $re = '/^(\w+[.-]?\w+[.-]?\w+)+@(\w+[.-]?\w+[.]\w+)+$/';
  return preg_match( $re, $email, $m );
}

function clean_alphanumeric_plus($str)
{
  $str = trim($str);
  return ereg_replace("[^A-Za-z0-9_-]",'',$str);
}

function clean_alphanumeric($str)
{
  $str = trim($str);
  return ereg_replace("[^A-Za-z0-9 |]",'',$str);
}
function clean_numeric($str)
{
  $str = trim($str);
  return ereg_replace("[^0-9]",'',$str);
}

function valid_password($password)
{
  return (strlen($password)>=5);
}

/**
 * CONTENT RETRIEVEAL FUNCTION
 */
function get_testimony($pagename)
{
  $sql = "SELECT `id`,`pagename`,`email`,`name`,`website`,`title`,`body`,`date`,`views` 
          FROM `testimony` WHERE `pagename` LIKE '$pagename' AND status='1' LIMIT 0,1";
  $res = dbQuery($sql);
  if( $row = dbFetchAssoc($res) ){
    update_views($table='testimony',$row['id']);
    return $row;
  }
  return;
}
function get_testimony_count()
{
  $sql = "SELECT count(*) as `num` FROM `testimony` WHERE `status`='1'";
  $res = dbQuery($sql);
  $row = dbFetchAssoc($res);
  return $row['num'];
}
function get_all_testimony($page=1)
{
  $arr_results = array();
  $page = ($page=='') ? 1 : $page;
  $start_limit = ($page*10) - 10;
  $sql = "SELECT `id`,`pagename`,`email`,`name`,`website`,`title`,`body`,`date`,`views` 
          FROM `testimony` WHERE status='1' ORDER BY `date` DESC LIMIT $start_limit,10";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_results []= $row;
  }
  if(count($arr_results)>0) return $arr_results;
  else return;
}
function get_new_testimony()
{
  $arr_results = array();
  $sql = "SELECT `id`,`pagename`,`email`,`name`,`website`,`title`,`body`,`date`,`views` 
          FROM `testimony` WHERE status='0' ORDER BY `date` DESC";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_results []= $row;
  }
  if(count($arr_results)>0) return $arr_results;
  else return;
}
function get_photo($photo)
{
  $sql = "SELECT `id`,`photoname`,`email`,`name`,`website`,`title`,`description`,`filename`,`date`,`views` 
          FROM `witness` WHERE `photoname` LIKE '$photo' AND status='1' LIMIT 0,1";
  $res = dbQuery($sql);
  if( $row = dbFetchAssoc($res) ){
    update_views($table='witness',$row['id']);
    return $row;
  }
  return;
}
function get_photo_count()
{
  $sql = "SELECT count(*) as `num` FROM `witness` WHERE `status`='1'";
  $res = dbQuery($sql);
  $row = dbFetchAssoc($res);
  return $row['num'];
}
function get_all_photo($page=1)
{
  $arr_results = array();
  $page = ($page=='') ? 1 : $page;
  $start_limit = ($page*10) - 10;  
  $sql = "SELECT `id`,`photoname`,`email`,`name`,`website`,`title`,`description`,`filename`,`date`,`views` 
          FROM `witness` WHERE status='1' ORDER BY `date` DESC LIMIT $start_limit,10";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_results []= $row;
  }
  if(count($arr_results)>0) return $arr_results;
  else return;
}
function get_new_photo()
{
  $arr_results = array();
  $sql = "SELECT `id`,`photoname`,`email`,`name`,`website`,`title`,`description`,`filename`,`date`,`views` 
          FROM `witness` WHERE status='0' ORDER BY `date` DESC";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_results []= $row;
  }
  if(count($arr_results)>0) return $arr_results;
  else return;
}
function get_video($video)
{
  $sql = "SELECT `id`,`videoname`,`email`,`name`,`website`,`title`,`description`,`videourl`,`date`,`views` 
          FROM `video` WHERE `videoname` LIKE '$video' AND status='1' LIMIT 0,1";
  $res = dbQuery($sql);
  if( $row = dbFetchAssoc($res) ){
    update_views($table='video',$row['id']);
    return $row;
  }
  return;
}
function get_video_count()
{
  $sql = "SELECT count(*) as `num` FROM `video` WHERE `status`='1'";
  $res = dbQuery($sql);
  $row = dbFetchAssoc($res);
  return $row['num'];
}
function get_all_video($page=1)
{
  $arr_results = array();
  $page = ($page=='') ? 1 : $page;
  $start_limit = ($page*5) - 5;  
  $sql = "SELECT `id`,`videoname`,`email`,`name`,`website`,`title`,`description`,`videourl`,`date`,`views` 
          FROM `video` WHERE status='1' ORDER BY `date` DESC LIMIT $start_limit,5";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_results []= $row;
  }
  if(count($arr_results)>0) return $arr_results;
  else return;
}
function get_new_video()
{
  $arr_results = array();
  $sql = "SELECT `id`,`videoname`,`email`,`name`,`website`,`title`,`description`,`videourl`,`date`,`views` 
          FROM `video` WHERE status='0' ORDER BY `date` DESC";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_results []= $row;
  }
  if(count($arr_results)>0) return $arr_results;
  else return;
}
function get_new_links()
{
  $arr_results = array();
  $sql = "SELECT `id`,`title`,`link`,`date` 
          FROM `links` WHERE status='0' ORDER BY `date` ASC";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_results []= $row;
  }
  if(count($arr_results)>0) return $arr_results;
  else return;
}
function get_author($author,$page=1)
{
  $arr_author = array();
  $page = ($page=='') ? 1 : $page;
  $start_limit = ($page*5) - 5;
  $sql = "SELECT `id`,`pagename`,`email`,`name`,`website`,`title`,`body`,`date`,`views` 
          FROM `testimony` WHERE status='1' AND `name` LIKE '$author'
          ORDER BY `date` DESC
          LIMIT $start_limit,5";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_author['testimony'] []= $row;
  }
  $sql = "SELECT `id`,`photoname`,`email`,`name`,`website`,`title`,`description`,`filename`,`date`,`views` 
          FROM `witness` WHERE status='1' AND `name` LIKE '$author'
          ORDER BY `date` DESC
          LIMIT $start_limit,5";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_author['witness'] []= $row;
  }
  if(count($arr_author)>0) return $arr_author;
  else return;
}
function get_author_count($author)
{
  $arr_author = array();
  $sql = "SELECT * 
          FROM `testimony` WHERE status='1' AND `name` LIKE '$author'";
  $res = dbQuery($sql);
  $num = dbNumRows($res);
  $sql = "SELECT *
          FROM `witness` WHERE status='1' AND `name` LIKE '$author'";
  $res = dbQuery($sql);
  $num += dbNumRows($res);
  return $num;
}
function get_search($search,$page=1)
{
  $arr_search = array();
  $page = ($page=='') ? 1 : $page;
  $start_limit = ($page*5) - 5;
  $sql = "SELECT `id`,`pagename`,`email`,`name`,`website`,`title`,`body`,`date`,`views` 
          FROM `testimony` WHERE status='1' AND (
          `name` LIKE '$search' OR
          `title` LIKE '%$search%' OR
          `body` LIKE '%$search%')
          ORDER BY `date` DESC
          LIMIT $start_limit,5";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_search['testimony'] []= $row;
  }
  $sql = "SELECT `id`,`photoname`,`email`,`name`,`website`,`title`,`description`,`filename`,`date`,`views` 
          FROM `witness` WHERE status='1' AND (
          `name` LIKE '$search' OR
          `title` LIKE '%$search%' OR
          `description` LIKE '%$search%')
          ORDER BY `date` DESC
          LIMIT $start_limit,5";
  $res = dbQuery($sql);
  while( $row = dbFetchAssoc($res) ){
    $arr_search['witness'] []= $row;
  }
  if(count($arr_search)>0) return $arr_search;
  else return;
}
function get_search_count($search)
{
  $arr_search = array();
  $sql = "SELECT * 
          FROM `testimony` WHERE status='1' AND (
          `name` LIKE '$search' OR
          `title` LIKE '%$search%' OR
          `body` LIKE '%$search%')";
  $res = dbQuery($sql);
  $num = dbNumRows($res);
  $sql = "SELECT *
          FROM `witness` WHERE status='1' AND (
          `name` LIKE '$search' OR
          `title` LIKE '%$search%' OR
          `description` LIKE '%$search%')";
  $res = dbQuery($sql);
  $num += dbNumRows($res);
  return $num;
}

/**
 * sends back an upload error message
 *
 * @param unknown_type $errno
 * @return unknown
 */
function get_upload_error($errno)
{
  	switch($errno)
  	{
  		case '1':
  			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
  			break;
  		case '2':
  			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
  			break;
  		case '3':
  			$error = 'The uploaded file was only partially uploaded';
  			break;
  		case '4':
  			$error = 'No file was uploaded.';
  			break;
  		case '6':
  			$error = 'Missing a temporary folder';
  			break;
  		case '7':
  			$error = 'Failed to write file to disk';
  			break;
  		case '8':
  			$error = 'File upload stopped by extension';
  			break;
  		case '999':
  		default:
  			$error = 'No error code avaiable';
  	}
  	
  	return $error;
}

?>