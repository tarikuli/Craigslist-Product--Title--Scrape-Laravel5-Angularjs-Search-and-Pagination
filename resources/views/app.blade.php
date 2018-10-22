<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Craigslist Product Title Scrape using Laravel 5.2, Angular 1.x and Bootstrap 3.x</title>

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	
	<!-- Angular JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js"></script>  
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular-route.min.js"></script>

	<!-- MY App -->
	<script src="{{ asset('/app/packages/dirPagination.js') }}"></script>
	<script src="{{ asset('/app/routes.js') }}"></script>
	<script src="{{ asset('/app/services/myServices.js') }}"></script>
	<script src="{{ asset('/app/helper/myHelper.js') }}"></script>

	<!-- App Controller -->
	<script src="{{ asset('/app/controllers/ItemController.js') }}"></script>

</head>
<body ng-app="main-App">
	<nav class="navbar navbar-default">
		<div class="container-fluid">

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="#/items">Craigslist Product Title Scrape using Laravel 5.2, Angular 1.x and Bootstrap 3.x </a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
		<ng-view></ng-view>
	</div>

	<!-- Scripts -->

</body>
</html>
