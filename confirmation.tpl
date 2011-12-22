{if $status == 'failed'}
<p>{l s='We could not process your payment successfully' mod='samuraifeefighters'}
<br /><br />
<u>{l s='Reason' mod='samuraifeefighters'} :</u><br />
{$errors}
{else}
<p>{l s='Your order' mod='samuraifeefighters'} <span class="bold">#{$orderid}</span> {l s='on' mod='samuraifeefighters'} <i>{$shop_name}</i> {l s='is complete.' mod='samuraifeefighters'}
	<br /><br /><span class="bold">{l s='Your will receive an email with the complete order details now.' mod='samuraifeefighters'}</span>
{/if}
	<br /><br />{l s='For any questions or for further information, please contact our' mod='samuraifeefighters'} <a href="{$base_dir_ssl}contact-form.php">{l s='customer support' mod='samuraifeefighters'}</a>.
</p>