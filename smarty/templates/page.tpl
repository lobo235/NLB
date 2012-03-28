<html>
	<head>
		<title>{$title|escape}</title>
	</head>
	<body>
		<h1>{if isset($pageTitle)}{$pageTitle|escape}{else}{$title|escape}{/if}</h1>
		<div id="container">
			{$content}
		</div>
	</body>
</html>
