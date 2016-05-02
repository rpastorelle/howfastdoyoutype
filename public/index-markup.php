<?php 

//$phraseInfo = getPhrase(10);
$phraseInfo = getPhrase();

$uiColors = array('black','hotpink','burntorange','red','mustard','yellow','orange','green','blue','white');

?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>How Fast Do You Type?</title>
	<meta name="description" content="How fast do you type is a fun typing game to test your words per minute (wpm). Find out how your typing skills compare.">
	<meta name="author" content="Ryan Pastorelle">

	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<meta property="og:type" content="website">
    <meta property="og:title" content="How Fast Do You Type?" /> 
    <meta property="og:image" content="http://howfastdoyoutype.com/images/og-screenshot.jpg" /> 
    <meta property="og:description" content="How fast do you type is a fun typing game to test your words per minute (wpm). Find out how your typing skills compare." /> 
    <meta property="og:url" content="http://howfastdoyoutype.com/">

	<link rel="stylesheet" href="css/style.css?v=1">
	
    <script src="js/libs/prefixfree-1.0.4.min.js"></script>
	<script src="js/libs/modernizr-2.0.6.min.js"></script>
</head>
<body class="" data-color="">

<div id="container">
	<header>
        <div id="logo"><span>how</span><span>fast</span><span>do</span><span>you</span><span>type</span><span>?</span></div>
        <div id="animate">
            <span>h</span><span>o</span><span>w</span><span>f</span><span>a</span><span>s</span><span>t</span><span>d</span><span>o</span><span>y</span><span>o</span><span>u</span><span>t</span><span>y</span><span>p</span><span>e</span><span>?</span>
        </div>
	</header>
	
	<div id="main" role="main">
    
        <form>
            <div id="phrase"><?php echo strtolower( $phraseInfo['phrase'] ); ?></div>
            <textarea id="entry" class="trans" placeholder="how fast do you type..."></textarea>
            <div id="note">type to begin. press <kbd>enter</kbd> when done</div>
            <div id="timer" class="trans">00</div>
            <div id="log">
                <div id="wpm" class="trans"></div>
                <div id="errors" class="trans"></div>
                <div><kbd>s</kbd> for stats</div>
            </div>
            <input type="hidden" name="phraseId" value="<?php echo $phraseInfo['id']; ?>" id="phraseId" />
        </form>
    
	</div>
	
	<footer>
        <div>
        	
        	<!-- Facebook Like Button -->
        	<div id="fb-like" class="fb-like" data-send="false" data-width="400" data-show-faces="false" data-font="lucida grande"></div>
        	<div id="fb-root"></div>
        	
            <section id="glossary">
                <h3>What does it mean?</h3>
                <dl>
                	<dt><abbr title="Words per minute">WPM:</abbr></dt>
                	<dd>Words per minute. A word is standardized to five characters or keystrokes, including spaces and punctuation.</dd>
                	
                	<dt><abbr title="Net words per minute">NWPM:</abbr></dt>
                	<dd>Words per minute minus 2 for each error.</dd>
                	
                	<dt><abbr title="Errors">Errors:</abbr></dt>
                	<dd>Number of incorrect terms. Terms are separated by spaces.</dd>
                	
                	<dt><abbr title='Does not qualify'>DNQ:</abbr></dt>
                	<dd>Does not qualify. Entry must be within 10 characters of the phrase to qualify.</dd>
                	
                	<dt><abbr title='Pangram'>Pangram:</abbr></dt>
                	<dd>A sentence using every letter of the alphabet at least once.</dd>
                </dl>
            </section>
            <section id="controls">
              	<h4>CONTROLS</h4>
              	<p>type to begin</p>
              	<p>[h]elp / shortcuts <kbd>h</kbd></p>
              	<p>stop clock <kbd>enter</kbd> or <kbd>esc</kbd></p>
                <p>[n]ext phrase <kbd>&rarr;</kbd> or <kbd>n</kbd></p>
                <p>[r]etry phrase <kbd>R</kbd></p>
                <p>[s]tatistics <kbd>S</kbd></p>
                <p>set [u]sername <kbd>U</kbd></p>
                <p>replay animation[.] <kbd>.</kbd></p>
            </section>
        </div>
        <div id="credits">
        	<p>
        		<strong>how fast do you type?</strong> i'm working on it. it's here, for now on the real internet. &mdash;<a href="http://twitter.com/#!/rpastorelle/">Ryan Pastorelle</a></p>
        </div>
	</footer>
</div> <!--! end of #container -->
<div id="help" class="overlay">
    <div>
    	<span style='float:right;'><kbd>ESC</kbd> close</span>
        <h4>Keyboard Shortcuts</h4>
        <table>
            <tr><td><kbd>enter</kbd> or <kbd>esc</kbd></td><td>Stop timer</td></tr>
            <tr><td><kbd>U</kbd></td><td>Set [U]sername</td></tr>
            <tr><td><kbd>N</kbd> or <kbd>&rarr;</kbd></td><td>[N]ext</td></tr>
            <tr><td><kbd>R</kbd></td><td>[R]etry Phrase</td></tr>
            <tr><td><kbd>S</kbd></td><td>[S]tatistics</td></tr>
            <tr><td><kbd>.</kbd></td><td>Replay Animation[.]</td></tr>
            <tr><td><kbd>h</kbd></td><td>[H]elp</td></tr>
        </table>
    </div>
</div>
<div id="scoreboard" class="overlay">
    <div>
    	<span style='float:right;'><kbd>ESC</kbd> close</span>
        <h4>STATISTICS</h4>
        <h3><strong><?php echo $phraseInfo['name']; ?></strong></h3>
        <p>
        	Type: <strong><?php echo ucwords( $phraseInfo['type'] ); ?></strong><br />
            <strong><?php echo strlen( $phraseInfo['phrase'] ); ?></strong> Keystrokes<br /> 
        </p>
        <p id="stats_recap"></p>
    </div>
</div>
<div id="userscreen" class="overlay">
    <div>
    	<span style='float:right;'><kbd>ESC</kbd> close</span>
        <h4>Set Username</h4>
        <p>
        	<input type="text" name="username" id="username" />
        	<button id="saveUsername">Save</button>
        </p>
    </div>
</div>


<!-- Scripts at bottom for quick load -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<!-- scripts concatenated and minified via ant build script-->
<script src="js/plugins.js"></script>
<script src="js/script.js"></script>
<!-- end scripts-->

<!-- Facebook JS SDK -->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=166708750081030";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php if( $_SERVER['HTTP_HOST']=='howfastdoyoutype.com' ): ?>
<script>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30846975-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php endif; ?>

<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
	<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
<![endif]-->

</body>
</html>
