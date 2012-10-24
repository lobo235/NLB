<table class="admin-table">
	<tr class="admin-table-header-row">
{$columns=$template_obj->getColumns()}
{$columncount=0}
{foreach from=$columns item=table}
{foreach from=$table item=column}
{$columncount=$columncount+1}
		<th>{$column->getName()|replace:'_':' '|ucwords|escape}</th>
{/foreach}
{/foreach}
	</tr>
{foreach from=$objects item=obj}
	<tr class="{cycle values="odd,even"}">
{foreach from=$columns item=table}
{foreach from=$table item=column}
		<td>{$obj->getField($column->getName())|truncate:256:"...":true|escape}</td>
{/foreach}
{/foreach}
		<td class="actions-column">
{$class=get_class($obj)}
			[<a href="{$app->l("admin/db-object/edit/$class/`$obj->getField($obj->getPrimaryIdColumn())`")}">edit</a>]
			[<a href="{$app->l("admin/db-object/delete/$class/`$obj->getField($obj->getPrimaryIdColumn())`")}" class="delete-confirm">delete</a>]
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="{$columncount}" align="center">No Rows</td>
	</tr>
{/foreach}
</table>