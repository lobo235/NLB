<table class="admin-table">
	<tr class="admin-table-header-row">
		<th>Entity ID</th>
		<th>Entity Type</th>
		<th>Name/Identifier</th>
		<th>Actions</th>
	</tr>
{foreach from=$entities item=e}
	<tr class="{cycle values="odd,even"}">
		<td>{$e->getEid()}</td>
		<td>{$e->getType()|escape}</td>
		<td>{$e->getIdentifier()|escape}</td>
		<td class="actions-column">
			[<a href="{$app->l("admin/entity/edit/`$e->getEid()`")}">edit</a>]
			[<a href="{$app->l("admin/entity/delete/`$e->getEid()`")}">delete</a>]
		</td>
	</tr>
{/foreach}
</table>