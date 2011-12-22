<?php

/**

 * Prestashop Samurai Fee Fighters Payment Module

 * @package    Samurai Fee Fighters Payment Package

 * @copyright  Copyright (c) 2011 www.jsmwebsolutions.com

 * @author     Andy Singh (cherryworld4u@gmail.com)

 * @creation date	8th December,2011
 
 * @website	www.jsmwebsolutions.com

 */
require_once dirname(__FILE__).'/api/samurai-client-php/lib/Samurai.php';

class Samuraifeefighters extends PaymentModule

{

	private	$_html = '';

	private $_postErrors = array();



	public function __construct()

	{

		$this->name 	= 'samuraifeefighters';

		$this->tab 		= 'Payment';

		$this->version 	= '1.0';
		
		$this->payment_done = false;
	
		$this->config = array(

							'title'		=> 'Pay via Credit Card on Samurai',

							'sandbox'		=> 'true',

							'merchantKey'	=> '',

							'merchantPassword'	=> '',

							'processorToken'		=> '',
							
							'redirectUrl'		=> '',

						);	

		

        parent::__construct();



		$this->page 			= basename(__FILE__, '.php');

        $this->displayName 		= $this->l('Samurai Fee Fighters');

        $this->description 		= $this->l('Payments by Samurai Gateway');

		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');

	}


	public function install()

	{

		$flag = true;

		if (parent::install()) {

			foreach ($this->config as $key => $value) {

				if (!empty($value) && !Configuration::updateValue($key, $value)) {

					$flag = false;

					break;

				}

			}

		} else {

			$flag = false;

		}

		

		if ($flag == true && (!$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))) {

			$flag = false;

		}



		return $flag;

	}


	public function uninstall()

	{

		$flag = true;

		foreach ($this->config as $key => $value) {

			if (!Configuration::deleteByName($key)) {

				$flag = false;

				break;

			}

		}		

		

		if ($flag == true && !parent::uninstall()) {

			$flag = false;

		}

		

		return $flag;

	}


	public function getContent()

	{

		$this->_html = '<h2>Samurai Fee Fighter</h2>';

		if (isset($_POST['submitSamurai']))

		{

			if (empty($_POST['merchantKey'])) {

				$this->_postErrors[] = $this->l('Samurai Merchant Key is required for this module to work.');

			} else if (empty($_POST['merchantPassword'])) {

				$this->_postErrors[] = $this->l('Samurai Merchant Password is required for this module to work.');

			}else if (empty($_POST['processorToken'])) {

				$this->_postErrors[] = $this->l('Samurai Processor Token is required for this module to work.');

			}
			else if (empty($_POST['redirectUrl'])) {

				$this->_postErrors[] = $this->l('Samurai Redirect URL is required for this module to work.');

			}
			

			if (!sizeof($this->_postErrors)) {

				Configuration::updateValue('title', $_POST['title']);

				Configuration::updateValue('sandbox', $_POST['sandbox']);

				Configuration::updateValue('merchantKey', $_POST['merchantKey']);

				Configuration::updateValue('merchantPassword', $_POST['merchantPassword']);

				Configuration::updateValue('processorToken', $_POST['processorToken']);
				
				Configuration::updateValue('redirectUrl', $_POST['redirectUrl']);				

				$this->displayConf();

			} else {

				$this->displayErrors();

			}

		}

		$this->displaySamuraiFeeFighters();

		$this->displayFormSettings();

		return $this->_html;

	}


	public function displaySamuraiFeeFighters()
	{
		$this->_html .= '
		<div style="float: right; width: 340px; height: 100px; border: dashed 0px #666; padding: 0px; margin-left: 12px;">
			<div style="clear: both;"></div>
			<img src="../modules/samuraifeefighters/SamuraiFeeFightersLogo.jpg" style="float:left; margin-right:15px;" />
			<div style="clear: right;"></div>
		</div>
		<b>'.$this->l('This module allows you to accept payments by Samurai Fee Fighters.').'</b>
		<div style="clear:both;">&nbsp;</div>';
	}
	
	
	public function displayFormSettings()
	{

		$config_keys 	= array_keys($this->config);

		$conf 			= Configuration::getMultiple($config_keys);
		

		$sandbox 				= array_key_exists('sandbox', $_POST) 				? $_POST['sandbox'] 			: (array_key_exists('sandbox', $conf) 			? $conf['sandbox'] 		: '');

		$title 			= array_key_exists('title', $_POST) 		? $_POST['title'] 		: (array_key_exists('title', $conf) 		? $conf['title'] 		: '');

		$merchantKey 				= array_key_exists('merchantKey', $_POST) 			? $_POST['merchantKey'] 			: (array_key_exists('merchantKey', $conf) 		? $conf['merchantKey'] 		: '');

		$merchantPassword		= array_key_exists('merchantPassword', $_POST) 		? $_POST['merchantPassword'] 	: (array_key_exists('merchantPassword', $conf) 		? $conf['merchantPassword'] 	: '');

		$processorToken 	= array_key_exists('processorToken', $_POST)	? $_POST['processorToken'] 	: (array_key_exists('processorToken', $conf) 		? $conf['processorToken'] 		: '');
		
		$redirectUrl 	= array_key_exists('redirectUrl', $_POST)	? $_POST['redirectUrl'] 	: (array_key_exists('redirectUrl', $conf) 		? $conf['redirectUrl'] 		: '');


		$this->_html .= '

		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="clear: both;">

		<fieldset>

			<legend><img src="../img/admin/contact.gif" />'.$this->l('Samurai Payment Gateway Configuration').'</legend>

			<label>'.$this->l('Title').'</label>

			<div class="margin-form"><input type="text" size="33" name="title" value="'.htmlentities($title, ENT_COMPAT, 'UTF-8').'" /></div>

			<label>'.$this->l('Merchant Key').'</label>

			<div class="margin-form"><input type="text" size="33" name="merchantKey" value="'.htmlentities($merchantKey, ENT_COMPAT, 'UTF-8').'" /></div>

			<label>'.$this->l('Merchant Password').'</label>

			<div class="margin-form"><input type="text" size="33" name="merchantPassword" value="'.htmlentities($merchantPassword, ENT_COMPAT, 'UTF-8').'" /></div>

			<label>'.$this->l('Processor Token').'</label>

			<div class="margin-form"><input type="text" size="33" name="processorToken" value="'.htmlentities($processorToken, ENT_COMPAT, 'UTF-8').'" /></div>
			
			<label>'.$this->l('Redirect Url').'</label>

			<div class="margin-form"><input type="text" size="33" name="redirectUrl" value="'.htmlentities($redirectUrl, ENT_COMPAT, 'UTF-8').'" /></div>
			
			<label>'.$this->l('Sandbox Mode').'</label>
			
			<div class="margin-form">
				<input type="radio" name="sandbox" value="1" '.($sandbox ? 'checked="checked"' : '').' /> <label class="t">'.$this->l('Yes').'</label>
				<input type="radio" name="sandbox" value="0" '.(!$sandbox ? 'checked="checked"' : '').' /> <label class="t">'.$this->l('No').'</label>
			</div>

			<br /><br /><center><input type="submit" name="submitSamurai" value="'.$this->l('Update settings').'" class="button" /></center>

		</fieldset>

		</form><br /><br />';

	}
	

	public function displayConf()
	{

		$this->_html .= '

		<div class="conf confirm">

			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />

			'.$this->l('Settings updated successfully').'

		</div>';

	}


	public function displayErrors()

	{

		$nbErrors = sizeof($this->_postErrors);

		$this->_html .= '

		<div class="alert error">

			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>

			<ol>';

		foreach ($this->_postErrors AS $error)

			$this->_html .= '<li>'.$error.'</li>';

		$this->_html .= '

			</ol>

		</div>';

	}


	public function hookPayment($params)

	{

		if (!$this->active)

			return ;

		global $smarty;

		$smarty->assign(array(

			'this_path' => $this->_path,

			'this_path_ssl' => Tools::getHttpHost(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/',

			'payment_method_title' => Configuration::get('title')

		));

		return $this->display(__FILE__, 'samuraifeefighters.tpl');

	}



	public function hookPaymentReturn($params)

	{

		if (!$this->active)

			return ;

		global $smarty, $cookie;

		$state = $params['objOrder']->getCurrentState();

		if ($state == _PS_OS_PAYMENT_) {

			$smarty->assign('status', 'ok');

			$smarty->assign('orderid', str_pad($params['objOrder']->id, 6, '0', STR_PAD_LEFT));

		} else {

			$smarty->assign('status', 'failed');

			$smarty->assign('code', $cookie->errors);

		}

		

		return $this->display(__FILE__, 'confirmation.tpl');

	}



	function askCC($cart,$paymentMethodToken,$errorsResponse=false) {

		if (!$this->active)

			return ;

		global $cookie, $smarty;	
		
		/*----------------Call Samurai Library Files---------------------*/
		
		Samurai::setup(array(
		  'sandbox'          => Configuration::get('sandbox'),
		  'merchantKey'      => Configuration::get('merchantKey'),
		  'merchantPassword' => Configuration::get('merchantPassword'),
		  'processorToken'   => Configuration::get('processorToken')
		));
		
		$ajax        = isset($ajax)        ? $ajax				: false;
		
		$redirectUrl = Configuration::get('redirectUrl'); 
		
		$classes     = isset($classes)     ? $classes			: '';
				
		$transaction = isset($transaction) ? $transaction : new Samurai_Transaction();
		
		$paymentMethod = isset($paymentMethod) ? $paymentMethod : new Samurai_PaymentMethod();
		
		$site		 = 'https://api.samurai.feefighters.com/v1//payment_methods';//;Samurai::$site;
		
		$merchantKey = Samurai::$merchantKey;
		
		$sandbox	 = Samurai::$sandbox;
		
		$paymentMethodToken = $paymentMethodToken;
		
		/*---------------------------------------------------------------*/
		
		
		$smarty->assign(array(

			'nbProducts' => $cart->nbProducts(),

			'total' => $cart->getOrderTotal(true, 3),

			'this_path' => $this->_path,

			'this_path_ssl' => Tools::getHttpHost(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/',

			'cc_month' => date('m'),

			'cc_year' => date('Y'),

			'payment_errors' => $cookie->errors,
			
			'merchantKey' => $merchantKey,
			
			'sandbox' => $sandbox,
			
			'paymentMethodToken' => $paymentMethodToken,
			
			'site' => $site,
			
			'redirectUrl' => $redirectUrl,
			
			'errorsResponse' => $errorsResponse

		));

		

		// unset the error messages

		$cookie->errors = '';

		unset($cookie->errors);

		return $this->display(__FILE__, 'getCC.tpl');

	}

	

	function doPayment($cart) {

		if (!$this->active)

			return ;

		

		if ($cart->nbProducts() <= 0) {

			Tools::redirect('modules/samuraifeefighters/payment.php');

		}



		global $cookie, $smarty;



		/*$errors = $this->validateCC();

		if (!empty($errors)) {

			$this->go_back_with_payment_error($errors, false);

		}*/


		$amount 	= $cart->getOrderTotal(true, 3);

		$orderid 	= $cart->id . '-' . $amount . '-' . substr(uniqid(),-4);

		$tax 		= 0;

		$subtotal 	= $amount;

		$ship	 	= 0;
		
		$state		= 2;

		
		// Store the order with the status and message

		parent::validateOrder($cart->id, $state, $amount, $this->name, $message, $extraVars);

		$this->payment_done = true;

		

		// Reset the cookie data

		unset($cookie->error_count);

		

		return $this->payment_done;

	}

	

	function go_back_with_payment_error($msg, $inc=true) {

		global $cookie;

		$cookie->errors = $msg;

		if ($inc != false) $cookie->error_count++;

		Tools::redirect('modules/samuraifeefighters/payment.php');

	}
		

	function validateCC() {

		$errors = '';

		foreach($_POST['credit_card'] as $key=>$val):
		
			switch($key):			
				case 'first_name':
					if($val==''):
						$errors .= '<p>Please enter first name.</p>';
					endif;
				break;
				case 'last_name':
					if($val==''):
						$errors .= '<p>Please enter last name.</p>';
					endif;
				break;
				case 'address_1':
					if($val==''):
						$errors .= '<p>Please enter address.</p>';
					endif;
				break;
				case 'city':
					if($val==''):
						$errors .= '<p>Please enter city.</p>';
					endif;
				break;
				case 'state':
					if($val==''):
						$errors .= '<p>Please enter state.</p>';
					endif;
				break;
				case 'zip':
					if($val==''):
						$errors .= '<p>Please enter zip.</p>';
					endif;
				break;
				case 'card_number':
					if($val==''):
						$errors .= '<p>Please enter card number.</p>';
					elseif(!(16 <= strlen($val) && strlen($val) <= 19)):
						$errors .= '<p>Invalid card number</p>';
					endif;
				break;
				case 'expiry_month':			
					if($val==''):					
						$errors .= '<p>Please enter expiry month.</p>';											
					elseif (!(1 <= intval($val) && intval($val) <= 12)):		
						$errors .= '<p>Invalid card expiry month</p>';		
					endif;			
				break;
				case 'expiry_year':
					$ccyear_now  = date('Y');
					if($val==''):
						$errors .= '<p>Please enter expiry year.</p>';
					elseif($ccyear_now >intval($val)):
						$errors .= '<p>Card expiry date should be in the future</p>';					
					endif;
				break;
				case 'cvv':
					if($val==''):
						$errors .= '<p>Please enter CVV.</p>';
					elseif(!(3 <= strlen($val) && strlen($val) <= 4)):
						$errors .= '<p>CVV2 is a 3 to 4 digit number</p>';
					else:
						$cvv2 = intval($_POST['cc_cvv2']);
							if (!(100 <= $cvv2 && $cvv2 <= 9999)):
								$errors .= '<p>CVV2 is a 3 to 4 digit number</p>';		
							endif;
					endif;
				break;
			
			endswitch;
		endforeach;
	

		return $errors;

	}

}

