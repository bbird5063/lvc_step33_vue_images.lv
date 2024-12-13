<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Scripts -->
	@vite(['resources/sass/app.scss', 'resources/js/app.js'])

	<title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
	<div id="app" class="p-5">
		@yield('content')
	</div>
</body>

</html>