{$node->getBody()}
{if $userService->userHasRole($user, 'admin user')}
[<a href="/admin/db-object/edit/{$node->getType()|escape}/{$node->getNid()}">edit</a>]
[<a href="/admin/db-object/delete/{$node->getType()|escape}/{$node->getNid()}" class="delete-confirm">delete</a>]
{/if}