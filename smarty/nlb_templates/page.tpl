<html>
	<head>
		<title>{if isset($title)}{$title|escape}{else}{$smarty.const.SITE_NAME}{/if}</title>
		{if $assets}{asset_combine files=$assets}{/if}
	</head>
	<body>
		<a class="site-logo" href="{$app->urlRoot()}"><h2>{$smarty.const.SITE_NAME|escape}</h2></a>
		<div id="container">
			<div id="left">
				<div id="main-content">
					<h1>{if isset($pageTitle)}{$pageTitle|escape}{elseif isset($title)}{$title|escape}{else}{$smarty.const.SITE_NAME}{/if}</h1>
					{if isset($content)}{$content}{/if}
				</div>
			</div>
			<div id="right">
				<div id="right-content">
					This is the right
				</div>
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		<div id="footer" class="center">
			&copy; {$smarty.now|date_format:'%Y'} {$smarty.const.SITE_NAME|escape}
		</div>
	</body>
</html>
