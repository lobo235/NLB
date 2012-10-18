{foreach from=$faqs item=faq}
<a href="#q{$faq->getFaqid()}" class="scroll">{$faq->getQuestion()|escape}</a><br />
{/foreach}

<hr />

{foreach from=$faqs item=faq}
<h3 id="q{$faq->getFaqid()}">{$faq->getQuestion()|escape}</h3>
{$faq->getAnswer()}
{/foreach}