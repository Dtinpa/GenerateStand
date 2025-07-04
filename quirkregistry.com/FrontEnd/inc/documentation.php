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

   <!-- Bootstrap core JavaScript -->
    <script src="/FrontEnd/vendor/jquery/jquery.min.js"></script>
    <script src="/FrontEnd/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="/FrontEnd/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/FrontEnd/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="/FrontEnd/js/jqBootstrapValidation.js"></script>
	
    <script type="text/javascript" src="/FrontEnd/documentation.js"></script>
    <script src='//ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js' type='text/javascript'></script>

    <!-- stand chart -->
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <!-- clipboard class -->
    <script src="/FrontEnd/js/clipboard/dist/clipboard.min.js"></script>
	
  </head>
  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-secondary fixed-top text-uppercase" id="mainNav">
        <div class="col-xs-6 text-center">
            <a class="navbar-brand js-scroll-trigger" href="/">What's Your Stand?</a>
        </div>
	    <div class="col-xs-6 text-center">
            <a class="navbar-brand js-scroll-trigger" href="/FrontEnd/inc/documentation.php">API Documentation</a>
        </div>
    </nav>

    <header class="masthead bg-primary text-center headerName">
		<div class="containerCustom">
      <div class="text-uppercase mb-0 artistHeader">API Access is given with a 24h expiration.  Use the documentation below to prepare your script.</div>
			<div class="text-uppercase mb-0 artistHeader">Enter Email</div>
			<input autocomplete="off" class="tertiaryColor artist col-xs-10 col-sm-5 col-md-4 float-none" type="text" id="emailBox" title="Enter Search Keyword" />
			<button class="getAPIKey primaryColor artistButton artist col-xs-5 col-sm-2 col-md-3 col-lg-1 float-none" id="getAPIKey">Enter</button>
		</div>
	</header>

    <header id="docHeader" class="masthead headerHeight headerStats">
		<div id="docContainer" class="containerCustom">
        <div id="endpoints" class="col-xs-6">
            <h1 class="quirkHeader">Endpoints</h1>
            <ul class="col-xs-6 quirkHeader endpointListContainer">
                <li class="endpointListBullet token">Token</li>
                <li class="endpointListBullet action">Action</li>
                <li class="endpointListBullet activity">Activity</li>
                <li class="endpointListBullet body">Body</li>
                <li class="endpointListBullet limit">Limit</li>
                <li class="endpointListBullet power">Power</li>
                <li class="endpointListBullet special">Special</li>
                <li class="endpointListBullet weak">Weak</li>
            </ul>
        </div>
        <div id="documentation" class="col-xs-6">
          <h1 class="quirkHeader textAlignRight">Documentation</h1>
          <div id="endpointDesc">
          </div>
        </div>
		</div>
  </body>
</html>