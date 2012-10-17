<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{if isset($title)}{$title|escape}{else}{$smarty.const.NLB_SITE_NAME}{/if}</title>
		{if $assets}{asset_combine files=$assets}{/if}
	</head>
	<body>
		<div id="outer-container">
			<a class="site-logo" href="{$app->urlRoot()}"><h2>{$smarty.const.NLB_SITE_NAME|escape}</h2></a>
			<div id="container">
				<div id="left" class="{if !isset($sidebar1_content)}expand{/if}">
					<div id="main-content">
						<h1>{if isset($pageTitle)}{$pageTitle|escape}{elseif isset($title)}{$title|escape}{else}{$smarty.const.NLB_SITE_NAME}{/if}</h1>
{if isset($content)}{$content}{/if}
					</div>
				</div>
{if isset($sidebar1_content)}
				<div id="right">
					<div id="right-content">
						{$sidebar1_content}
					</div>
				</div>
{/if}
				<div class="clear">&nbsp;</div>
			</div>
			<div id="footer" class="center">
				&copy; {$smarty.now|date_format:'%Y'} {$smarty.const.NLB_SITE_NAME|escape}
			</div>
		</div>
	</body>
</html>
