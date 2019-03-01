{extends file='parent:frontend/listing/product-box/product-price.tpl'}

{$smarty.block.parent}

{block name='frontend_listing_box_article_price_default'}
    <span class="price--default is--nowrap{if $sArticle.has_pseudoprice} is--discount{/if}">
        <p> sdasdasashdh</p>
    </span>
{/block}