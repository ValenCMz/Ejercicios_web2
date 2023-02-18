{include file="header.tpl" }

<ul>
{foreach from=$pokemones item=$pokemon }
    <li>{$pokemon->apodo}</li>
{/foreach}
</ul>

{include file="footer.tpl" }