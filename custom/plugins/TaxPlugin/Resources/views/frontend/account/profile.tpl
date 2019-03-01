{extends file="parent:frontend/account/profile.tpl"}

        {* VatId *}
        {block name="frontend_account_profile_profile_input_vatid"}
            <div class="profile--vatid">
                <input name="profile[attribute][vatid]"
                       type="text"
                       required="required"
                       aria-required="true"
                       placeholder="{s name='RegisterPlaceholderVatId' namespace="frontend/register/personal_fieldset"}{/s}"
                       value="{$vatId}"
                       class="profile--field is--required{if $errorFlags.vatid} has--error{/if}"
                />
            </div>
        {/block}



