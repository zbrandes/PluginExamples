{extends file='parent:frontend/checkout/ajax_add_article.tpl'}

{debug}

{*{block name='checkout_ajax_add_title'}*}
    {*<div class="modal--title">*}
        {*<h1> {$statusArticle} </h1>*}
        {*<h1> {$ordernumber} </h1>*}
        {*<h1> {$quantity} </h1>*}
        {*<h1> Test123: {$test123}  </h1>*}
    {*</div>*}
{*{/block}*}

{block name='checkout_ajax_add_information_name'}


    {if $sArticle.sConfigurator}
        {if $sArticle.sConfiguratorSettings.type == 1}
            {$file = 'frontend/detail/config_step.tpl'}
        {elseif $sArticle.sConfiguratorSettings.type == 2}
            {$file = 'frontend/detail/config_variant.tpl'}
        {else}
            {$file = 'frontend/detail/config_upprice.tpl'}
        {/if}
        {include file=$file}
    {/if}


    {block name='checkout_ajax_add_actions_checkout'}

        {if {$statusArticle} == 'hasVariant'}

                    {$url = {url controller=checkout action=addArticle}}
                    {if $url}



                            <form name="sAddToBasket" method="post" action="{url controller=checkout action=addArticle}" class="buybox--form" data-add-article="true" data-eventName="submit"{if $theme.offcanvasCart} data-showModal="false" data-addArticleUrl="{url controller=checkout action=ajaxAddArticleCart}"{/if}>

                                <input type="hidden" name="sAdd" id="sAdd" value="{$sArticle.ordernumber}">
                                <input type="hidden" name="sActionIdentifier" value="">
                                <input type="hidden" name="sAddAccessories" id="sAddAccessories" value="">
                                <input type="hidden" name="preventVariant" value="true">

                                {if (!isset($sArticle.active) || $sArticle.active)}
                                    {if $sArticle.isAvailable}
                                        <div class="select-field">

                                            {$maxQuantity=$sArticle.maxpurchase+1}
                                            {if $sArticle.laststock && $sArticle.instock < $sArticle.maxpurchase}
                                                {$maxQuantity=$sArticle.instock+1}
                                            {/if}

                                            <select id="sQuantity" name="sQuantity" class="quantity--select">
                                                {section name="i" start=$sArticle.minpurchase loop=$maxQuantity step=$sArticle.purchasesteps}
                                                    <option value="{$smarty.section.i.index}">{$smarty.section.i.index}{if $sArticle.packunit} {$sArticle.packunit}{/if}</option>
                                                {/section}
                                            </select>

                                        </div>
                                        <div class="buybox--button">

                                                    <button class="buybox--button block btn is--primary is--icon-right is--center is--large">
                                                            {s namespace="frontend/listing/box_article" name="ListingBuyActionAdd"}{/s}<i class="icon--basket"></i> <i class="icon--arrow-right"></i>
                                                    </button>

                                        </div>
                                    {else}
                                        <p> $sArticle.isAvailable </p>
                                    {/if}
                                {else}
                                    <p> $sArticle.active || $sArticle.active </p>
                                {/if}

                            </form>

                    {else}
                        <p> URL-Failure </p>
                    {/if}
        {else}
            <a href="{url action=cart}" title="{s name='AjaxAddLinkCart'}{/s}" class="link--confirm btn is--primary right is--icon-right is--large">
                {s name='AjaxAddLinkCart'}{/s} <i class="icon--arrow-right"></i>
            </a>
        {/if}

        <script type="text/javascript">
            if($('.configurator--form.upprice--form').length > 0)
            {
                var varON = '{$sArticle.ordernumber}';
                $('select').change(function()
                {
                    $('.modal--article .buybox--button.block.btn.is--primary.is--icon-right.is--center.is--large').attr('disabled', 'disabled');
                    $.ajax({
                        url: '/GetVariant',
                        method: 'post',
                        data: {
                            formData: JSON.stringify($('.configurator--form.upprice--form').serializeArray()),
                            artNr: varON
                        }
                    }).done(function(data)
                    {
                        $('.modal--article .buybox--button.block.btn.is--primary.is--icon-right.is--center.is--large').removeAttr('disabled');
                        $('#sAdd').val(data);
                        console.log('333');
                    });
                });
            }

        </script>

    {/block}

    {*{include sfile="frontend/listing/product-box/button-buy.tpl" meinParam="test"}*}

    {*{$file = 'frontend/detail/config_upprice.tpl'}*}
    {*{if $sArticle.sConfigurator}*}
    {*{if {$statusArticle} == 'hasVariant'}*}
    {*{include file=$file}*}
    {*{/if}*}
    {*{else}*}
    {*<p> sConfigurator-Failure </p>*}
    {*{/if}*}

    {*{foreach $sArticle as $article}*}
        {*{if $article.sConfigurator}*}
            {*{if $article.sConfiguratorSettings.type == 1}*}
                {*{$file = 'frontend/detail/config_step.tpl'}*}
            {*{elseif $article.sConfiguratorSettings.type == 2}*}
                {*{$file = 'frontend/detail/config_variant.tpl'}*}
            {*{else}*}
                {*{$file = 'frontend/detail/config_upprice.tpl'}*}
            {*{/if}*}
            {*{include file=$file}*}
        {*{/if}*}
    {*{/foreach}*}

{/block}