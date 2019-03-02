<?php

namespace MyPlugin;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Model\ModelEntity;
use Shopware\Models\Customer\Group;

class MyPlugin extends Plugin {

	public static function getSubscribedEvents() 
	{
		return [
			'Shopware_Modules_Order_SendMail_Send' => 'modifyOrderMail'
		];
	}

		
		public function modifyOrderMail(\Enlight_Event_EventArgs $args) {
			
				$controller = $args->get('subject');
				echo '<pre>';
				print_r($controller->sBasketData['content'][0]['tax_rate']); 
				echo '</pre>';
				die();

				$OrderNumber = Shopware()->Modules()->sOrder()->sGetOrderNumber();
				$FillOrderNumber = $OrderNumber -1;
				
				if($OrderNumber != null) {
					print_r("OrderNumber: ".$FillOrderNumber);
				} else {
					print_r("OrderNumber ist nicht gefüllt");
				}
		
				echo "<br>";

				$MyOrderID = Shopware()->Db()->fetchOne('SELECT orderID FROM s_order_details ORDER BY id DESC LIMIT 1');
				// print_r($MyOrderID);

				$GroupKeyById = Shopware()->Modules()->sOrder()->getCustomerInformationByOrderId($MyOrderID);
				// print_r($GroupKeyById['customergroup']);

				$groupKey = Shopware()->Models()->getRepository(\Shopware\Models\Customer\Group::class)->findOneBy(["groupkey" => $GroupKeyById['customergroup']]);
				$groupDescription = Shopware()->Models()->getRepository(\Shopware\Models\Customer\Group::class)->findOneBy(["description" => $groupKey]);

                                $GroupDesc = Shopware()->Db()->fetchOne('SELECT description FROM s_core_customergroups WHERE groupkey = ?', array($controller->sUserData['additional']['user']['customergroup']));
                                var_dump($groupDescription);

				// hier ziehst du bloß irgendeine Gruppe - es fehlt ein Where - besser über die Resourses ziehen (Shopware\Models\Customer\Group)

				$OrderNumberById = Shopware()->Modules()->sOrder()->getOrderDetailsByOrderId($MyOrderID)[0];

				$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Order></Order>');
			
				$orderdata = $xml->addChild('Oderdata');
				$orderdata->addChild('OrderNumber', $OrderNumber);
				// $orderdata->addChild('OrderID', $orderid);
				// $orderdata->addChild('ArticleID', $controller->sBasketData['content'][0]['articleID']);
				$orderdata->addChild('Orderdate', $OrderVar2['sOrderDay']." ".$OrderVar2['sOrderTime']);
				// $orderdata->addChild('OrderTime', $OrderVar2['sOrderTime']);

				$basketdata = $xml->addChild('Basketdata');
				$articles = $basketdata->addChild('Articles');
				$articles->addChild('Articlenumber', $controller->sBasketData['content'][0]['ordernumber']);
				$articles->addChild('Articlename', $controller->sBasketData['content'][0]['articlename']);
				$articles->addChild('ArticleDescription', $controller->sBasketData['content'][0]['additional_details']['description_long']);
				$articles->addChild('Price', $OrderVar2['sOrderDetails'][0]['price']);
				$articles->addChild('TaxRate', $OrderVar2['sOrderDetails'][0]['tax_rate']);
				$articles->addChild('Quantity', $controller->sBasketData['content'][0]['quantity']);
			
				$customerdata = $xml->addChild('Customerdata');
				$customerdata->addChild('UserID', $controller->sBasketData['content'][0]['userID']);
				$customerdata->addChild('CustomerGroup', $GroupDesc);
				$customerdata->addChild('Salutation', $OrderVar2['additional']['user']['salutation']);
				$customerdata->addChild('Firstname', $OrderVar2['billingaddress']['firstname']);
				$customerdata->addChild('Lastname', $OrderVar2['billingaddress']['lastname']);
				$customerdata->addChild('Street', $OrderVar2['billingaddress']['street']);
				$customerdata->addChild('Zipcode', $OrderVar2['billingaddress']['zipcode']);
				$customerdata->addChild('City', $OrderVar2['billingaddress']['city']);
				$customerdata->addChild('Country', $OrderVar2['additional']['country']['countryname']);
				$customerdata->addChild('Email', $OrderVar2['additional']['user']['email']);

				$shippingdata = $xml->addChild('Shippingdata');
				$shippingdata->addChild('Firstname', $OrderVar2['shippingaddress']['firstname']);
				$shippingdata->addChild('Lastname', $OrderVar2['shippingaddress']['lastname']);
				$shippingdata->addChild('Street', $OrderVar2['shippingaddress']['street']);
				$shippingdata->addChild('Zipcode', $OrderVar2['shippingaddress']['zipcode']);
				$shippingdata->addChild('City', $OrderVar2['shippingaddress']['city']);
				$shippingdata->addChild('Country', $OrderVar2['additional']['countryShipping']['countryname']);

				$paymentdata = $xml->addChild('Paymentdata');
				$paymentdata->addChild('Name', $OrderVar2['additional']['payment']['name']);
				$paymentdata->addChild('Description', $OrderVar2['additional']['payment']['description']);
				// $paymentdata->addChild('AdditionalDescription', $OrderVar2['additional']['payment']['additionaldescription']);

				$costdata = $xml->addChild('Totaldata');
				$costdata->addChild('AmountPriceIncl', $OrderVar2['sAmount']);
				$costdata->addChild('AmountPriceExcl', $OrderVar2['sAmountNet']);
				$costdata->addChild('Tax', $controller->sBasketData['content'][0]['tax']);
				$costdata->addChild('ShippingCost', $OrderVar2['sShippingCosts']);
				// $costdata->addChild('Tax', $controller->sBasketData['content'][0]['tax_rate']);

				$xmlDOM = dom_import_simplexml($xml)->ownerDocument;
				$xmlDOM->formatOutput = true;
				$xmlDOM->save('custom/plugins/MyPlugin/Ori-Order.xml');		 

	 }
}
