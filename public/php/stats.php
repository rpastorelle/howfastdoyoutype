<?php
/**
 * stats.php
 * @rpastorelle
 */

// Std Includes:
$base = realpath(dirname(__FILE__) . '/..');
include_once( $base."/src/std-includes.php" );


// Collect stats:
$phraseId = $_POST['phrase_id'];
$userId = $_POST['user_id'];
$username = $_POST['username'];
$ms = $_POST['milliseconds'];
$wpm = $_POST['wpm'];
$errors = $_POST['errors'];
$nwpm = $_POST['nwpm'];
$color = $_POST['color'] != '' ? $_POST['color'] : 'black';
$isMobile = ( $_POST['isMobile'] == 'true' );
$isTablet = ( $_POST['isTablet'] == 'true' );
$timestamp = time();

if( $userId=='' ){
    // Create a username:
    $res = dbQuery( "SELECT Auto_increment FROM information_schema.tables WHERE table_name='users' AND table_schema = '".DBNAME."'" );
    $row = dbFetchAssoc( $res );
    $autoInc = $row['Auto_increment'];
    $res = dbQuery( "INSERT INTO `users` SET `timestamp`='{$timestamp}', username='user{$autoInc}'" );
    $userId = dbInsertId();
    $username = 'user'.$userId;
}else{

    // Just in case it changed:
    $res = dbQuery( "UPDATE `users` SET `username`='{$username}' WHERE `id`='{$userId}'" );

}

// Record stats:
$sql = "INSERT INTO `stats` SET `phrase_id`='{$phraseId}', `user_id`='{$userId}', `milliseconds`='{$ms}',
			`wpm`='{$wpm}', `errors`='{$errors}', `nwpm`='{$nwpm}', `color`='{$color}',
			`isMobile`='{$isMobile}', `isTablet`='{$isTablet}', `timestamp`='{$timestamp}'";
$res = dbQuery( $sql );
$sid = dbInsertId();

// ------------------------------------
// Get avg stats for this phrase:
// ------------------------------------
$sql = "SELECT AVG(`milliseconds`) AS 'avg_ms', AVG(`wpm`) AS 'avg_wpm', AVG(`nwpm`) AS 'avg_nwpm', AVG(`errors`) AS 'avg_errors'
		FROM `stats` WHERE `phrase_id`='{$phraseId}' AND `isDNQ`=0
		GROUP BY `phrase_id`";
$res = dbQuery( $sql );
$avgStats = dbFetchAssoc($res);

$sql = "SELECT AVG(`nwpm`) as 'cavg_nwpm' FROM `stats` WHERE `color`='{$color}' AND `isDNQ`=0  GROUP BY `color`";
$res = dbQuery( $sql );
$row = dbFetchAssoc($res);
$avgStats['color_nwpm'] = $row['cavg_nwpm'];


// Get users rank:
$userRanks = array();
$sql = "SELECT `id`, @curRow := @curRow + 1 AS row_number FROM `stats` JOIN (SELECT @curRow := 0) r WHERE `phrase_id`='{$phraseId}' AND `isDNQ`=0  ORDER BY `nwpm` DESC";
$res = dbQuery( $sql );
while( $row = dbFetchAssoc($res) ){
    $statId = $row['id'];
    $userRanks[$statId] = $row['row_number'];
}
$attempts = count( $userRanks );


echo <<<JSON
{
	"stat_id": "{$sid}",
	"phrase_id": "{$phraseId}",
	"user_id": "{$userId}",
	"username": "{$username}",
	"milliseconds": "{$ms}",
	"wpm": "{$wpm}",
    "nwpm": "{$nwpm}",
	"errors": "{$errors}",
	"color": "{$color}",
	"isMobile": "{$isMobile}",
	"isTablet": "{$isTablet}",
	"timestamp": "{$timestamp}",
	"rank": "{$userRanks[$sid]} of {$attempts}",
	"avgs": {
		"ms": "{$avgStats['avg_ms']}",
		"wpm": "{$avgStats['avg_wpm']}",
		"nwpm": "{$avgStats['avg_nwpm']}",
		"errors": "{$avgStats['avg_errors']}",
		"color_nwpm": "{$avgStats['color_nwpm']}"
	}
}
JSON;

?>