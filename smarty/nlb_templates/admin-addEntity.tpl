<form method="post" action="{$app->urlRoot()}admin/entity/save">
<table class="entity-form">
{foreach from=$columns  item=column}
{$extras=$column->getExtras()}
	<tr class="entitity-form-row">
		<td class="entity-form-pair">
{if !$column->isType('hidden')}
			<div class="entity-form-label">{$column->getName()|replace:'_':' '|ucwords}:</div>
{/if}
{if $column->isType('hidden')}
			<input type="hidden" name="{$column->getName()}"{if $entity->getField($column->getName()) != NULL} value="{$entity->getField($column->getName())|escape}"{/if} />
{elseif $column->isType('string')}
			<input type="text" name="{$column->getName()}"{if $entity->getField($column->getName()) != NULL} value="{$entity->getField($column->getName())|escape}"{/if}{if $column->getMaxLength() > 0} maxlength="{$column->getMaxLength()}"{/if}{if $extras.size} size="{$extras.size}"{/if} />
{elseif $column->isType('text')}
			<textarea name="{$column->getName()}"{if $column->getMaxLength() > 0} maxlength="{$column->getMaxLength()}"{/if}{if $extras.rows} rows="{$extras.rows}"{/if}{if $extras.cols} cols="{$extras.cols}"{/if}>{if $entity->getField($column->getName()) != NULL}{$entity->getField($column->getName())|escape}{/if}</textarea>
{elseif $column->isType('radio')}
{foreach from=$extras.radio item=radio_opt_name key=radio_opt_id}
			<input id="{$column->getName()}{$radio_opt_id}" type="radio" name="{$column->getName()}" value="{$radio_opt_id}"{if $entity->getField($column->getName()) == $radio_opt_id} selected="selected"{/if} /><label for="{$column->getName()}{$radio_opt_id}"> {$radio_opt_name|escape}</label><br />
{/foreach}
{/if}
		<td>
	</tr>
{/foreach}
	<tr class="entity-form-buttons">
		<td>
			<input type="reset" name="reset" value="Reset" />
			<input type="submit" name="submit" value="Save" />
		</td>
	</tr>	
</table>
</form>