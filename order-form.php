<?php

    // Get POST vars:
    $hasRequestData = count( $_REQUEST );
    $form = $_REQUEST['form'];
    $name = $_REQUEST['name'];
    
    // Process form results:
    
    
    
    
    // display page: 
    // vvvvvvvvvvvvvvvvvvvvvvvv
?>
<!doctype html>
<html>
	<head>
		
		<title>Order Form</title>
		
		<script>
			
		</script>
		
		<style>
		
			ul{
				margin: 0;
				padding: 0;
				list-style: none;
			}
		
		</style>
	</head>
	<body>
		<div id=form>
		
			<?php if( $hasRequestData ): ?>
			
			<h3>Your Sumission:</h3>
			<ul>
				<li>Name: <?php echo $name; ?></li>
			</ul>
			<pre>$_GET:
			<?php print_r($_GET); ?>
			</pre>
			<pre>$_POST:
			<?php print_r($_POST); ?>
			</pre>
			<pre>$_REQUEST:
			<?php print_r($_REQUEST); ?>
			</pre>
			
			<hr />
			 
			<?php endif; ?>
		
			<h1>Order Form</h1>
			<form method="get" action="order-form.php">
				<ul>
					<li>
						<label for="name">Name:</label>
						<input type="text" size="40" name="name" id="name" />
					</li>
					<li>
						<input type="submit" name="form" value="Hit This" />
					</li>
				</ul>
			</form>		
		</div>
	</body>
</html> 