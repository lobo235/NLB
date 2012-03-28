<html>
	<head>
		<title>{if $title}{$title|escape}{else}{$smarty.const.SITE_NAME}{/if}</title>
		{if $assets}{asset_combine files=$assets}{/if}
	</head>
	<body>
		<h1>{if isset($pageTitle)}{$pageTitle|escape}{else}{$title|escape}{/if}</h1>
		<div id="container">
			{if $content}{$content}{/if}
		</div>
	</body>
</html>
