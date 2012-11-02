<span class="author">by <em>{$user->getFirstName()} {$user->getLastName()}</em></span>
{$node->getBody()}
{if $user->getUid() != 0}<a href="/admin/db-object/edit/{$node->getEid()}">edit</a>{/if}