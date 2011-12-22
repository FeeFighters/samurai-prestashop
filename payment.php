<?php
/**

 * Prestashop Samurai Fee Fighters Payment Module

 * @package    Samurai Fee Fighters Payment Package

 * @copyright  Copyright (c) 2011 www.jsmwebsolutions.com

 * @author     Andy Singh (cherryworld4u@gmail.com)

 * @creation date	8th December,2011
 
 * @website	www.jsmwebsolutions.com

 */

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/samuraifeefighters.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');

$linkpoint = new Samuraifeefighters();

Samurai::setup(array(
  'sandbox'          => Configuration::get('sandbox'),
  'merchantKey'      => Configuration::get('merchantKey'),
  'merchantPassword' => Configuration::get('merchantPassword'),	  
  'processorToken'   => Configuration::get('processorToken')
));

$paymentMethodToken = $_GET['payment_method_token'];

if($paymentMethodToken) {
	foreach(Currency::getCurrencies() as $currencyDetails):
		if($currencyDetails['id_currency']==$cookie->id_currency):
			$currency_code = $currencyDetails['iso_code'];			
		endif;
	endforeach;
	$processor = Samurai_Processor::theProcessor();
	$purchase  = $processor->purchase(
								 $paymentMethodToken, 
								 $cart->getOrderTotal(true, 3),
								 array(
									'currency_code' => $currency_code,
									'customer_reference' => time(),
									'billing_reference'  => time()
									
								 ));
	if ($purchase->isSuccess()) {		
		// get the payment
		$linkpoint->doPayment($cart);
		$order = new Order($linkpoint->currentOrder);
		Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.intval($cart->id).'&id_module='.intval($linkpoint->id).'&id_order='.$linkpoint->currentOrder.'&key='.$order->secure_key);				
	} else {
		/*$transaction   = isset($transaction) ? $transaction : new Samurai_Transaction();
		$paymentMethod = isset($paymentMethod) ? $paymentMethod : new Samurai_PaymentMethod();
		
		if ($transaction->hasErrors() || $paymentMethod->hasErrors()):
			if ($transaction->hasErrors()):
				foreach ($transaction->errors as $context => $errors):
					foreach ($errors as $error): 
						$errorsList .='<li>'.$error->description.'</li>';
					endforeach;
				endforeach;
			endif;
		endif;
		
		if ($paymentMethod->hasErrors()):
			foreach ($paymentMethod->errors as $context => $errors):
				foreach ($errors as $error):
					$errorsList .='<li>'.$error->description.'</li>';
				endforeach;
			endforeach;
		endif;*/
		
		echo $linkpoint->askCC($cart,$paymentMethodToken,true);
	}
} else {
	// get the CC information	
	echo $linkpoint->askCC($cart);
}



include_once(dirname(__FILE__).'/../../footer.php');
?>