<form method="post" action="{$app->urlRoot()}admin/db-object/{get_class($object)}/save" autocomplete="off">
	<table class="admin-form">
{foreach from=$columns  item=column}
{$extras=$column->getExtras()}
		<tr class="admin-form-row">
			<td class="admin-form-pair">
{if !$column->isType('hidden')}
				<div class="admin-form-label">
					<strong>{if isset($extras.label)}{$extras.label|escape}{else}{$column->getName()|replace:'_':' '|ucwords|escape}{/if}:</strong>
{if $column->isType('select') && $column->isType('multiple')}
					<em>(Multiple values can be selected by holding down CTRL while clicking)</em>
{/if}
				</div>
{/if}
{if $column->isType('hidden')}
{if $column->isType('uid')}
				<input type="hidden" name="{$column->getName()}" value="{if $object->getField($column->getName()) != NULL}{$object->getField($column->getName())|escape}{else}{$smarty.session.nlb_user_uid}{/if}" />
{else}
				<input type="hidden" name="{$column->getName()}"{if $object->getField($column->getName()) != NULL} value="{$object->getField($column->getName())|escape}"{/if} />
{/if}
{elseif $column->isType('string')}
				<input type="{if $column->isType('password')}password{else}text{/if}" name="{$column->getName()}" value="{if !$column->isType('password') && $object->getField($column->getName()) != NULL}{$object->getField($column->getName())|escape}{/if}"{if $column->getMaxLength() > 0} maxlength="{$column->getMaxLength()}"{/if}{if $extras.size} size="{$extras.size}"{else} size="30"{/if} />
{elseif $column->isType('text')}
				<textarea{if $column->isType('wysiwyg')} class="ckeditor"{/if} name="{$column->getName()}"{if $column->getMaxLength() > 0} maxlength="{$column->getMaxLength()}"{/if}{if $extras.rows} rows="{$extras.rows}"{/if}{if $extras.cols} cols="{$extras.cols}"{/if}>{if $object->getField($column->getName()) != NULL}{$object->getField($column->getName())|escape}{/if}</textarea>
{elseif $column->isType('radio')}
{foreach from=$extras.radio item=radio_opt_name key=radio_opt_id}
				<input id="{$column->getName()}{$radio_opt_id}" type="radio" name="{$column->getName()}" value="{$radio_opt_id}"{if $object->getField($column->getName()) == $radio_opt_id} checked="checked"{/if} /><label for="{$column->getName()}{$radio_opt_id}"> {$radio_opt_name|escape}</label><br />
{/foreach}
{elseif $column->isType('select')}
				<select name="{$column->getName()}{if $column->isType('multiple')}[]{/if}"{if $column->isType('multiple')} multiple="multiple"{/if}{if $extras.size} size="{$extras.size}"{/if}>
{foreach from=$extras.options item=option_name key=option_id}
{$selectedValues=$object->getField($column->getName())}
					<option value="{$option_id}"{if $selectedValues && in_array($option_id, $selectedValues)} selected="selected"{/if}>{$option_name|escape}</option>
{/foreach}
				</select>
{/if}
			<td>
		</tr>
{/foreach}
		<tr class="admin-form-buttons">
			<td>
				<input type="hidden" name="nlb_referrer" value="{if isset($smarty.server.HTTP_REFERER)}{$smarty.server.HTTP_REFERER}{/if}" />
				<input type="reset" name="r" value="Reset" />
				<input type="submit" name="s" value="Save" />
			</td>
		</tr>	
	</table>
</form>