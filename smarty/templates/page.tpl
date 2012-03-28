<html>
	<head>
		<title>{if $title}{$title|escape}{else}{$smarty.const.SITE_NAME}{/if}</title>
		{if $assets}{asset_combine files=$assets}{/if}
	</head>
	<body>
		<div id="container">
			<div id="left">
				<div id="main-content">
					<h1>{if isset($pageTitle)}{$pageTitle|escape}{else}{$title|escape}{/if}</h1>
					{if $content}{$content}{/if}
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
