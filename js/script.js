/* Author: Ryan Pastorelle @rpastorelle

	localStorage:
		- username
		- userId
		- uiColor
*/

// Grab localStorage:
var username = localStorage.getItem("username") || "",
	userId = localStorage.getItem("userId") || "",
	uiColor = localStorage.getItem("uiColor") || "",
	lastVisit = localStorage.getItem("lastVisit") || "",
	keystrokes = 0,
	d = new Date(),
	t = Math.floor( d.getTime() / 1000 ),
	playAnimation = ( !uiColor || (t - parseInt(lastVisit)) >= 3600 );
	
// Set last visit
//console.log(  (t - parseInt(lastVisit))  );
localStorage.setItem( "lastVisit", t );

// Only apply the next uiColor if lastVisit was more than day ago:
if( uiColor && !playAnimation ){
	$('body').addClass( uiColor ).attr('data-color',uiColor);
}



// -------------------------------------------
// Timer Object:
// -------------------------------------------
var Timer = (function(){
	
	//private
	var start_ms,
		end_ms,
		final_ms,
		isOn = false;
	
	return { 
		
		//exposed to public:
		
		isOn: function(){
			return isOn;
		},
		
		start: function(){
			var d = new Date();
			start_ms = d.getTime();
			end_ms = start_ms;
			isOn = true;
			return start_ms;
		},
		
		stop: function(){
			var d = new Date();
			end_ms = d.getTime();
			isOn = false;
			return end_ms;
		},
		
		getTime: function(){
			var finalMS = end_ms - start_ms,
				finalSec = (finalMS / 1000).toFixed( 4 );
			return parseFloat( finalSec );
		}
		
	}
	
}());

//-------------------------------------------
//HowFastDoYouType Object:
//-------------------------------------------
var HFDYT = (function(){
	
	// private:
	var uiColors = ['black','hotpink','burntorange','red','mustard','yellow','orange','green','blue','white'];
	
	return {
		// public:
		
		// @todo: 
		// isDNQ: function(){},
		
		isiPad: function(){ return navigator.userAgent.match(/iPad/i) != null },
		
		//Cred: http://detectmobilebrowsers.com/
		isMobile: function(){
			var a = navigator.userAgent || navigator.vendor || window.opera;
			if (/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))
				return true;
			return false;
		},
		
		isTablet: function(){
			var a = navigator.userAgent || navigator.vendor || window.opera;
			if (/android|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(ad|hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) 
				return !this.isMobile();
			return false;
		},
		
		getColor: function(){ return $("body").attr("data-color"); },
		
		getNextColor: function( c ){
			var tmpColors = uiColors,
				removeIx = tmpColors.indexOf(c);
			if( removeIx >= 0 ) tmpColors.splice( removeIx, 1 );
			var rand = Math.floor( Math.random() * tmpColors.length );
			return tmpColors[rand];
		}
	}
}());






// -------------------------------
// Public execution:
//-------------------------------

var start, 
	end, 
	tId,
	matchPhrase,
	$timer,
	$wpm,
	$errors,
	$log,
	$entry,
	$phrase,
	$container,
	$animate,
	$logo,
	$help,
	$scoreboard,
	$username,
	isDoneTyping = false, 
	isStartedTyping = false;

// Better setTimeout:
function goTimer(){

	//console.log( 'goTimer' );
	
	// Update timer:
	var timeTxt = $timer.text(),
		time = parseInt( timeTxt ) + 1;
	
	$timer.text( time );
	tId = setTimeout( goTimer, 1000 );

}


function getTimeDiff( start, end ){
	return Math.round( (end - start) / 1000 );
}

function checkMatch( e ){
	
	var $this = $(this),
		matchTest = $this.val().toLowerCase();
	
	// add to keystrokes to fight against cheatin' mo-fo's
	keystrokes += 1;
	
	if( !isStartedTyping ){
		
		// ESC pressed:
		if( e.keyCode==27 ){
			$this.blur();
			return;
		}
		
		//start timer:
		//console.log( 'startTimer' );
		Timer.start();
		tId = setTimeout( goTimer, 1000 );
		$logo.addClass('active');
	}
	
	if( !isDoneTyping ){
		
		//console.log( 'check ['+matchPhrase+'] == ['+matchTest+']' );
		//console.log( e );
		
		// Stop the timer for match, enter/esc:
		if( e.keyCode==13 || e.keyCode==27 || matchPhrase==matchTest ){
			Timer.stop();
			isDoneTyping = true;
			clearTimeout( tId );
			showFinalStats();
			$logo.removeClass('active');
			$this.blur();
			//return false;
		}
	}
	
	isStartedTyping = true;
}

/**
 * showFinalStats
 * - records final stats
 * - populates stats modal
 * - side-effect: creates a user if needed
 * - updates user, uiColor (localStorage)
 */
function showFinalStats(){
	
	var finalSec = Timer.getTime(),
	    entryVal = alltrim( $entry.val() ),
		matchChars = matchPhrase.length, // using matchPhrase (instead of entry) so stats are uniform
		incorrectParts = grade( matchPhrase, entryVal ),
		finalWPM = wpm( matchChars, finalSec ),
		finalNWPM = ( finalWPM - ( incorrectParts.length * 2 ) ).toFixed(1),
		entryChars = entryVal.length,
		charDiff = Math.abs( matchChars - entryChars );
		
	// Show WPM:
	$timer.text( finalSec );//.addClass("smallScale");
	$wpm.html( 'WPM: '+finalWPM+'<br />'+'NWPM: '+finalNWPM );//.addClass("largeScale");
	if( incorrectParts.length ){
		$errors.text( 'errors ('+incorrectParts.length+'): ' );
		$("<span>"+ incorrectParts.join(', ') +"</span>").appendTo( $errors );
	}
	
	// Record the stats:
	var stats = {
		phrase_id: $("#phraseId").val(),
		user_id: userId,
		username: username,
		milliseconds: (finalSec*1000),
		wpm: finalWPM,
		errors: incorrectParts.length,
		nwpm: finalNWPM,
		color: HFDYT.getColor(),
		isMobile: HFDYT.isMobile(),
		isTablet: HFDYT.isTablet()
	};
	
	// If not within 10 chars of the phrase length
	// then dont record these stats, theyre bogus:
	// -or-
	// If entered chars more than num of entered chars...
	// THEN I CAUGHT YOU CHEATING YOU COPY n PASTIN S.O.B!
	//console.log( 'k:', keystrokes, 'e:', entryChars );
	// 
	if( !isDNQ( charDiff, entryChars ) && finalNWPM < 250 ){
		
		// send stats to server
		$.ajax({
			type: 'POST',
			url: '/php/stats.php',
			data: stats,
			dataType: 'json',
			success: function( stats ){
				
				// Update the localStorage user:
				localStorage.setItem( "username", stats.username );
				localStorage.setItem( "userId", stats.user_id );
				
				userId = stats.user_id;
				username = stats.username;
				$username.val( username );
				
				// stats record is returned
				var $recap = $("#stats_recap").html(""),
					avgWPM = parseFloat( stats.avgs.wpm ).toFixed( 1 ),
					avgNWPM = parseFloat( stats.avgs.nwpm ).toFixed( 1 ),
					avgErrors = parseFloat( stats.avgs.errors ).toFixed( 1 ),
					colorAvgNWPM = parseFloat( stats.avgs.color_nwpm ).toFixed( 1 );
				$recap.append( "<p><strong>"+avgWPM+"</strong> avg WPM<br /><strong>"+avgNWPM+"</strong> avg NWPM<br /><strong>"+colorAvgNWPM+" "+stats.color+"</strong> avg NWPM<br /><strong>"+avgErrors+"</strong> avg errors</p>" );
				$recap.append( "<p class='modalFoot'><kbd>&rarr;</kbd> Next" );
				$recap.append( "<h5>Score for "+username+"</h5><p>"+$wpm.html()+"<br />RANK: "+stats.rank+"</p>" );
			}
		});
		
	}else{
		
		// They're probably a cheater
		// didnt mean the pre-req for counting:
		$("<em>(<abbr title='Does not qualify'>DNQ</abbr>)</em>").appendTo( $errors );
	}
	
	// Set the next uiColor:
	var d = new Date();
	localStorage.setItem( "uiColor", HFDYT.getNextColor( stats.color ) );
	localStorage.setItem( "lastVisit", d.getTime() );
}


// isDNQ: Find the cheaters
// charDiff	>> num of chars youre supposed to type vs num you did type
// entryChars >> num of chars in entry box
function isDNQ( charDiff, entryChars ){
	
	// character difference greater than 10?
	// YOU CHEAT!
	if( charDiff > 10 ) return true;
	
	// touch device and... less than 5 keystrokes!?
	// you pasted it in dude... and you cant fool me
	var isTouch = ( HFDYT.isMobile() || HFDYT.isTablet() );
	if( isTouch && keystrokes < 5 ) return true;
	
	// not a touch device and keystrokes are less than entered chars
	// thats a copy n paste fool if i ever met one myself
	if( !isTouch && ( (keystrokes+5) < entryChars ) ) return true; // spot them 5 extra keystrokes

	return false;
}

/* Returns words-per-minute
----------------------------*/
function wpm( chars, secs ){
	return ( (chars/5) / (secs/60) ).toFixed( 1 );
}

/* Returns array of incorrect
 * words in test variable
----------------------------*/
function grade( control, test ){

	var incorrectParts = [],
		controlParts = control.toLowerCase().split(' '),
		testParts = alltrim( test ).toLowerCase().split(' '),
		errStr = '';
		
	for( var i=0; i < controlParts.length; i++ ){
		if( controlParts[i] != testParts[i] ){
			errStr = testParts[i] || controlParts[i];
			incorrectParts.push( errStr );
		}
	}

	return incorrectParts;
}

function alltrim( str ){
    return str.replace(/^\s+|\s+$/g, '');
}


function addOverlay( $modal ){
	$('html, body').animate({ scrollTop: 0 }, 400);
	$container.addClass('transition textBlur'); 
	$modal.fadeIn();
}

function removeOverlay(){
	$(".overlay").fadeOut();
	$container.removeClass('textBlur').removeClass('transition');
}

function resetClock(){
	$timer.text('00'); 
	$errors.text(''); 
	$wpm.text(''); 
	$entry.val('').blur(); 
	isDoneTyping=false;
	isStartedTyping=false;
	keystrokes = 0;
}




function reloadAnimation(){
	
	var $letters = $animate.children("span");
	$letters.hide();
	$animate.show();
	$logo.fadeOut('norm',function(){
		animateGo( $letters, 0 );
	});
	
}

function animateGo( $ani, ix ){
	
	var $letter = $ani.eq( ix );
	if( $letter.length ){
		
		// Show the letters one after another:
		$letter.show('fast', function(){
			ix++;
			animateGo( $ani, ix );
		})
		
		
	}else{
		
		$animate.fadeOut('slow',function(){
			$entry.addClass('readying');
			$note.slideDown();
			$("<span>ready?</span>").appendTo("header").fadeIn().delay(1200).fadeOut('normal',function(){
				$("<span>set.</span>").appendTo("header").fadeIn().delay(1200).fadeOut('normal',function(){
					$("<span>go!</span>").appendTo("header").fadeIn().delay(1200).fadeOut('normal',function(){
						$logo.fadeIn();
					});
				});
			});
		});
		
	}
	
}



// - Ready, set go:
// ----------------------------
$(function(){
	
	$timer = $('#timer');
	$wpm = $('#wpm');
	$errors = $("#errors");
	$log = $("#log");
	$entry = $('#entry');
	$phrase = $('#phrase');
	$animate = $("#animate");
	$logo = $("#logo");
	$note = $("#note");
	$help = $("#help");
	$scoreboard = $("#scoreboard");
	$userscreen = $("#userscreen");
	$container = $("#container");
	$username = $("#username");
	
	matchPhrase = $phrase.text().toLowerCase();
	
	$('.trans').addClass('transition');
	
	// For iPad add ipad as body class:
	if( HFDYT.isiPad() ){
		$("body").addClass("ipad");
	}
	
	$entry.on({
		//keydown: function(){ console.log('keydown'); },
		//keyup: function(){ console.log('keyup'); },
		keyup: checkMatch
	});
	
	// start typing of howfastdoyoutype? logo:
	if( playAnimation ){ 
		animateGo( $animate.children("span"), 0 ); 
	}else{
		$logo.fadeIn();
		$note.show();
	}
	
	
	// Save username:
	$username.val( username );
	$("#saveUsername").click(function(e){
		e.preventDefault();
		var usernameInput = $username.val();
		if( usernameInput!='' ){
			localStorage.setItem( "username", usernameInput );
			username = usernameInput;
		}
		$username.val( username );
		removeOverlay();
	});
	
	
	// Add listeners for trafficLight end:
	var light = document.querySelector('#entry'),
		onTrannyEnd = function(e) {
			var tgt = e.target;
			tgt.classList.remove('readying');
			tgt.classList.add('ready');
			//$( tgt ).focus();
		};

	light.addEventListener("webkitAnimationEnd", onTrannyEnd, false);
	light.addEventListener("mozAnimationEnd", onTrannyEnd, false);
	light.addEventListener("oAnimationEnd", onTrannyEnd, false);
	light.addEventListener("animationEnd", onTrannyEnd, false);

	
});



//- Keyboard shortcuts:
//----------------------------
key('g, a, t, p, f, w, l, d, shift+\'', function(){ $entry.focus(); });
key('right, n', function(){ window.location.reload(); });
key('r', resetClock );
key('s', function(){ addOverlay( $scoreboard ) } ); // scores
key('u', function(){ addOverlay( $userscreen ) } ); // scores
key('h', function(){ addOverlay( $help ) } ); // halp!!
key('esc', removeOverlay );
key('.', reloadAnimation );

