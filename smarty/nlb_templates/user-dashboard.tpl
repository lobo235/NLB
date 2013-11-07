Welcome, {$user->getFirstName()|escape}

{if $userService->userHasRole($user, 'admin user')}
	<a href="{$app->urlRoot()}admin/db-objects/entity">Admin Page</a>
{/if}