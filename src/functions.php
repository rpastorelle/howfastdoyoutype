<?php

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
    // $resrc = dbQuery( $query );
    // return dbInsertId();
    return null;
}

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

function client_user_agent()
{
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
}

function http_host()
{
    return array_get($_SERVER, 'HTTP_HOST', 'cli');
}

function array_get($array, $key, $default = null)
{
    if (is_null($key)) {
        return $array;
    }
    if (isset($array[$key])) {
        return $array[$key];
    }
    foreach (explode('/', $key) as $segment) {
        if (! is_array($array) || ! array_key_exists($segment, $array)) {
            return $default;
        }
        $array = $array[$segment];
    }
    return $array;
}