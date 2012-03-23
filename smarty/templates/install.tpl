<h3>Failed Queries:</h3>
<ul>
{foreach $failedQueries as $query}
	<li class="failed-query"><pre>{$query|escape}</pre></li>
{/foreach}
</ul>

<h3>Successful Queries:</h3>
<ul>
{foreach $successfulQueries as $query}
	<li class="success-query"><pre>{$query|escape}</pre></li>
{/foreach}
</ul>