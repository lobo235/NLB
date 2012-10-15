<ul>
{foreach from=$entities item=e}
	<li>{$e->getEid()} - {$e->getType()|escape}</li>
{/foreach}
</ul>