<?
/*******************************************************
* classAuthValidate.php
* Copyright 2009, UrbanTrunkShow.com
*******************************************************/

// auth-validate var
$auth =	new classAuthValidate();

class classAuthValidate{

	// DB Props
	var $_groupIDnum					=	'';


	function classAuthValidate(){
      //constructor
  	}

	function setGroupIDnum($data){

		$this->_groupIDnum			=	$data;
	}

	function validate()
	{
	  global $cookie_expire, $cookie_renew;

  	// check to see if session exists:
		if ( $sessionID = $_COOKIE['sid'] )
		{
  		$query = "SELECT user_id FROM sessions WHERE id = '$sessionID' LIMIT 1;";
  		$query_result =	dbQuery($query);
  		if ( $query_result )
  		{
			  if ( dbNumRows($query_result) == 1 )
			  {
          // Match found, VALID Cookie:
          // Authentication Valid
          $validID 	= 1;
          $row	 	  = dbFetchAssoc($query_result);
          $user_id	=	$row['user_id'];
	   		}
	   		else
	   		{
      	  // Invalid Cookie:
      		$validID = 0;
   			}
 			}
 			else
 			{
			  $validID = 0;
 			}


 			// Authentication Valid?
			if ( $validID==1 )
			{
			  if($s_renew = $_COOKIE['s_renew'])
			  {
			    $curtime = time();
			    if($curtime > $s_renew)
			    {
			      $user_info = unserialize( stripslashes($_COOKIE['user_info']) );
            setcookie( 'sid', $sessionID, $cookie_expire );
            setcookie( 's_renew', $cookie_renew, $cookie_expire );
            setcookie( 'user_info', serialize($user_info), $cookie_expire);
			    }
			  }

        return true;
        /**************************************************
        // Authentication Valid, Check Authorization
        $query = "SELECT user_idnum FROM user_to_group WHERE user_idnum = '$user_id' AND group_idnum >= 1;";
        $query_result = dbQuery($query);

        if ( dbNumRows($query_result) == 1 )
        {
          // Authorization Passed
          return true;
				}
				else
				{
          // Authorization Failed
          // redirect to login page:
          login_redirect();
				}
				****************************************************/
      }
      else
      {
        // Invalid session, redirect to login:
        login_redirect();
			}

		} else {
      //No session saved on client side
      login_redirect();
    }

  }//end validate function

  /**
   * invalidate is a reverse validation
   * - let the user see the page only if they are not logged in
   * - if logged in: redirect to homepage
   *
   * @return unknown
   */
	function invalidate()
	{
  	// check to see if session exists:
		if ( $sessionID = $_COOKIE['sid'] )
		{
  		$query = "SELECT user_id FROM sessions WHERE id = '$sessionID' LIMIT 1;";
  		$query_result =	dbQuery($query);
  		if ( $query_result )
  		{
			  if ( dbNumRows($query_result) == 1 )
			  {
	      		// Match found, VALID Cookie:
	      		// Authentication Valid, User is logged in
	      		$validID 	= 1;

	   		} else {
      			// Invalid Cookie:
      			$validID 	= 0;
   			}

 			}
 			else
 			{
			  $validID = 0;
 			}

			if ( $validID==0 )
			{
        // No session, allow:
        return true;

      } else {

        //there is a session, redirect to homepage:
        homepage_redirect();
			}

		} else {
      //No session saved on client side
      return true;
    }

  }//end invalidate function

}

/**
 * UTILITY FUNCTIONS
 */
function login_redirect()
{
  // redirect to login page:
  $url  	= "login.html";
  $target = urlencode( "http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}" );
  $url 		.= "?target=$target";
  header( "Location: $url" );
  exit;
}

function homepage_redirect()
{
  // redirect to login page:
  $url  	= "home.html";
  header( "Location: $url" );
  exit;
}


?>