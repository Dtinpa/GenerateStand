<?php
	session_start();

	if(!isset($_SESSION['token'])) {
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	} 
	
	$token = $_SESSION['token'];
?>

<!--

This website is to be used to create a quirk similar to that of the show from
My Hero Academia.

-->

<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta name="keywords" content="Stand, stand, generation, Generation, Generator, generator, JoJo's Bizzarre Adventure, JoJo, Jolyne, creator, Creator">

    <title>JoJo's Bizarre Adventure: Stand Generator</title>

    <!-- Bootstrap core CSS -->
    <link href="/FrontEnd/vendor/bootstrap/css/bootstrap.min.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="/FrontEnd/vendor/fontawesome-free/css/all.min.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Plugin CSS -->
    <link href="/FrontEnd/vendor/magnific-popup/magnific-popup.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="/FrontEnd/css/freelancer.min.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" rel="stylesheet">
	<link href="/FrontEnd/css/bootstrap/bootstrap.min.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" rel="stylesheet">

	<!-- stand chart -->
	<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
	<!-- clipboard class -->
	<script src="/FrontEnd/js/clipboard/dist/clipboard.min.js"></script>
	
  </head>
  <body id="page-top">
	
	<div id='validate' hidden data-t="<?php echo($token); ?>"></div>
	<div id="copy" hidden data-power="" data-stats="" data-name=""></div>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-secondary fixed-top text-uppercase" id="mainNav">
      <div class="col-xs-12 text-center">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">What's Your Stand?</a>
      </div>
    </nav>

    <!-- User input -->
    <header class="masthead bg-primary text-center headerName">
		<div class="containerCustom">
			<img class="img-fluid d-block mx-auto image" src="/FrontEnd/img/jojoTitleTrans.png" alt="">
			<div class="text-uppercase mb-0 artistHeader">Enter Artist</div>
			<input autocomplete="off" class="tertiaryColor artist col-xs-10 col-sm-5 col-md-4 float-none" type="text" id="search-keyword" title="Enter Search Keyword" />
			<button class="primaryColor artistButton artist col-xs-5 col-sm-2 col-md-3 col-lg-1 float-none" id="standButton">Enter</button>
		</div>
	</header>
	<header id="statHeader" class="masthead text-center headerHeight headerStats">
		<div id="statContainer" class="containerCustom">
			<div id="stand" class="stats row no-gutters">
				<div class="displayInline displayStats col-xs-12 col-md-5 offset-md-1 col-lg-4 offset-lg-2" id="standStats"></div>
				<div class="displayInline displayAbility col-xs-10 col-md-5 col-lg-4 float-lg-left float-none" id="result">
					<div class="quirkCapsule" id="nameField">
						<div class='quirkHeader'>Name</div>
						<div class=quirkDiv id='name'>Country Roads</div>
					</div>
					<div class='quirkCapsule'>
						<div class='quirkHeader'>Ability</div>
						<div class='quirkDiv' id="powerName"></div>
					</div>
					<div class='quirkCapsule'>
						<div class='quirkHeader'>Info</div>
						<div class='quirkDiv' id="powerDesc"></div>
					</div>
					<div class='quirkCapsule'>
						<div class='quirkHeader'>Method of Activation</div>
						<div class='quirkDiv' id="activation"></div>
					</div>
					<div class='quirkCapsule'>
						<div class='quirkHeader'>Limit / More Susceptible To</div>
						<div class='quirkDiv' id="limit"></div>
					</div>
					<div class='quirkCapsule' id="rangeCapsule">
						<div class='quirkHeader'>Range</div>
						<div class='quirkDiv' id="range"></div>
					</div>
					<button class='artist copyButton secondaryColor col-xs-5 col-sm-5 col-md-4 float-none' id='copyButton'>Copy to Clipboard</button>
				</div>
			</div>
		</div>
    </header>

    <!-- Bootstrap core JavaScript -->
    <script src="/FrontEnd/vendor/jquery/jquery.min.js"></script>
    <script src="/FrontEnd/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="/FrontEnd/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/FrontEnd/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="/FrontEnd/js/jqBootstrapValidation.js"></script>
	
	<script type="text/javascript" src="/FrontEnd/ParseQuirk.js"></script>
	<script src='//ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js' type='text/javascript'></script>
	<script type="text/javascript">

		//document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js' type='text/javascript'>\<\/script>");
		
		function logError(log) {
			console.log(log);
		}
		
		var userValidation = ((tok = $('#validate').attr('data-t')) ? tok : "");
		
		function quirk() {	
			$.ajax({
				url: 'FrontEnd/quirkGenie.php',
				type: 'GET',
				data: {
					token: userValidation
				},
				dataType: "json",
				success: function(result) {
					if(typeof result["error"] != 'undefined') {
						logError(result["error"]);
					} else {					
						var result = parseQuirk(result);
						if(result) {
							$("#stand").hide();
							searchForSong();
							$("#statHeader").addClass("headerStatsNum").removeClass("headerHeight");
							$("#stand").fadeIn(2500, function() {});
						} else {
							logError(result);
						}
					}
						
				},
				error: function(e) {
					logError(e);
				}
			});
		}
		
		//Input Definitions

		//if the user hits enter, then just execute the click command
		$("#search-keyword").keyup(function(e) {
			e.preventDefault();	
			if(e.which == 13) {
				$('#standButton').click();
			}
		});

		// Theres an html principle of "seperation of content between behavior/action"
		// Just thought it might be good practice as opposed to using an onclick
		$('#standButton').click( function(e) {
			e.preventDefault();			
			buttonElement = document.getElementById("standButton");
			
			// Only execute this request if the link doesn't have this class
			// helps prevent spam
			if(!buttonElement.classList.contains('inactive')) {
				// If on the off chance a fatal error occurs, log the bug, but tell the user
				// They were just born without a quirk
				try {
					$("#statHeader").addClass("headerHeight").removeClass("headerStatsNum");
					quirk();
					$([document.documentElement, document.body]).animate({
						scrollTop: $("#statHeader").offset().top
					}, 1000);
				} catch(err) {
					logError(err.toString());
				}
				
				$("#stand").hide();
			
				// Adds the 'inactive' class to keep people from spamming the button
				buttonElement.classList.add('inactive');
				$('#standButton').css('visibility', 'hidden');
					setTimeout(function() { 
					buttonElement.classList.remove('inactive');
					$('#standButton').css('visibility', '');
					}, 5000);
			}					
				
			return false; 
		});

		//copy the Stand info to clipboard using a 3rd party project
		var clipboard = new ClipboardJS('#copyButton', {
			text: function() {
				var power = $("#copy").data("power");
				var stats = $("#copy").data("stats");
				var name = $("#copy").data("name");			
			
				return name + power + "\r\n" + stats;
			}
		});
	</script>
			<!--Google Analytics-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53823309-1', 'auto');
  ga('send', 'pageview');

</script>

	<!-- Default Statcounter code for Testing Tracking
	www.generatestand.com -->
	<script type="text/javascript">
	var sc_project=9963285; 
	var sc_invisible=1; 
	var sc_security="32e1150c"; 
	</script>
	<script type="text/javascript"
	src="https://www.statcounter.com/counter/counter.js"
	async></script>
	<noscript><div class="statcounter"><a title="hit counter"
	href="https://statcounter.com/" target="_blank"><img
	class="statcounter"
	src="https://c.statcounter.com/9963285/0/32e1150c/1/"
	alt="hit counter"></a></div></noscript>
	<!-- End of Statcounter Code -->
	</body>

</html>
