{capture name=path}{l s='Credit Card Payment via Samurai Fee Fighters' mod='samuraifeefighters'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Order summary' mod='samuraifeefighters'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.'}</p>
{else}
	{if $errorsResponse}
	  <div id="js_errors" class="error"><ul>{l s='The payment gateway declined the transaction. Please fill correct details and try again!' mod='samuraifeefighters'}</ul></div>
	{/if}
<h3>{l s='Credit Card payment' mod='samuraifeefighters'}</h3>
<form action="{$site}" method="post" >
<p style="margin-top:20px;">
	{l s='The total amount of your order is' mod='samuraifeefighters'}
	<span id="amount" class="price">{displayPrice price=$total}</span>
	{l s='(tax incl.)' mod='samuraifeefighters'}
</p>
    <input name="redirect_url" type="hidden" value="{$redirectUrl}" />
    <input name="merchant_key" type="hidden" value="{$merchantKey}" />
    <input name="sandbox" type="hidden" value="{$sandbox}" />
    {if ($paymentMethodToken)}<input name="payment_method_token" type="hidden" value="{$paymentMethodToken}" />{/if}
<table border="0" style="margin-left: 7px;">
  <tr>
    <td><label for="credit_card_first_name">First name</label></td>
	<td><input type="text" size="33" name="credit_card[first_name]" id="credit_card_first_name" value="" /></td>
  </tr>
  <tr>
    <td><label for="credit_card_last_name">Last name</label></td>
	<td><input type="text" size="33" name="credit_card[last_name]" id="credit_card_last_name" value="" /></td>
  </tr>  
  <tr>
    <td><label for="credit_card_address_1">Address 1</label></td>
	<td><input type="text" size="33" name="credit_card[address_1]" id="credit_card_address_1" value="" /></td>
  </tr>
  <tr>
    <td><label for="credit_card_address_2">Address 2</label></td>
	<td><input type="text" size="33" name="credit_card[address_2]" id="credit_card_address_2" value="" /></td>
  </tr>
  <tr>
    <td><label for="credit_card_city">City</label></td>
	<td><input type="text" size="33" name="credit_card[city]" id="credit_card_city" value="" /></td>
  </tr>
  <tr>
    <td><label for="credit_card_state">State</label></td>
	<td><input type="text" size="33" name="credit_card[state]" id="credit_card_state" value="" /></td>
  </tr>
  <tr>
    <td><label for="credit_card_zip">Zip</label></td>
	<td><input type="text" size="33" name="credit_card[zip]" id="credit_card_zip" value="" /></td>
  </tr>
  <tr>
    <td><label for="credit_card_card_number">Card Number</label></td>
	<td><input type="text" size="20" name="credit_card[card_number]" id="credit_card_card_number" value="" /></td>
  </tr>
  <tr>
    <td>Card Expiry</td>
	<td><input type="text" size="2" name="credit_card[expiry_month]" id="credit_card_expiry_month" value="{$cc_month}" />&nbsp;<input type="text" size="4" name="credit_card[expiry_year]" id="credit_card_expiry_year" value="{$cc_year}" />&nbsp; format: mm yyyy</td>
  </tr>
  <tr>
    <td><label for="credit_card_cvv">CVV</label></td>
	<td><input type="text" size="5" name="credit_card[cvv]" id="credit_card_cvv" value="" /></td>
  </tr>
</table>
<p>
	<b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='samuraifeefighters'}.</b>
</p>
<p class="cart_navigation">
	<a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Other payment methods' mod='samuraifeefighters'}</a>
	<input type="submit" name="submit" value="{l s='I confirm my order' mod='samuraifeefighters'}" class="exclusive_large" />
</p>
</form>
{/if}
