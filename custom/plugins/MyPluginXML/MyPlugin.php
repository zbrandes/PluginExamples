<?php

namespace MyPlugin;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Model\ModelEntity;
use Shopware\Models\Customer\Group;

class MyPlugin extends Plugin {

	public static function getSubscribedEvents() {
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

	

				// $group = Shopware()->Models()->getRepository(Group::class)->findOneBy(['id' => 1]);

				// $groupID = Shopware()->Models()->getRepository(\Shopware\Models\Customer\Group::class)->findOneBy('id');

				
                 

				


				
					
			 
				
			
				
				// foreach($controller->sBasketData['content'] as $key => $myRow) {
				//     $OrderDetailId = $controller->sBasketData['content'][$key]['orderDetailId'];
				//     $orderid = $OrderDetailId - 146; 
				// }
				
			



				 
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

 
				


				 

				// hier ziehst du bloß irgendeine Gruppe - es fehlt ein where - besser über die resourses ziehen (Shopware\Models\Customer\Group)

				



				



				/*$controller = $args->get('subject');
				$MyArgs = $args->getReturn();
				$getModelManager = $args->getEntityManager();
				$model = $args->getEntity();
				$order = $controller->getModelManager()->getRepository(\Shopware\Models\Article\Article::class)->find('id');
				$order = Shopware()->Models()->getRepository('ShipBundle:Shipment')->findOneBy(array('order_id' => $orderId));*/


				/*$OrderAttributes = $args->getReturn();
				$orderNumber = $OrderAttributes['ordernumber'];
				$order = $this->getModels()->getRepository('Shopware\Models\Order\Order')->findByOne(array('number' => $orderNumber));
				print_r(get_class_methods(get_class($args->getReturn())));*/








					 



				
				       


		

							 $OrderNumberById = Shopware()->Modules()->sOrder()->getOrderDetailsByOrderId($MyOrderID)[0];



								/*foreach ($OrderNumberById as $AllDetailsOrderById) {
									print_r($AllDetailsOrderById);
								}

								echo "<br>";

								foreach ($OrderNumberById as $OrderID) {
									print_r("OrderID: ".$OrderID["orderID"]);
								}

								echo "<br>";
				 
								foreach ($OrderNumberById as $articleID) {
									print_r("ArticleID: ".$articleID["articleID"]);
								}

								echo "<br>";

								foreach($OrderNumberById as $articleordernumber) {
									print_r("Articleordernumber: ".$articleordernumber["articleordernumber"]);
								}

								echo "<br>";

								foreach ($OrderNumberById as $price) {
									print_r("Price: ".$price["price"]);
								}

								echo "<br>";

								foreach ($OrderNumberById as $quantity) {
									print_r("Quantity: ".$quantity["quantity"]);
								}

								echo "<br>";

								foreach ($OrderNumberById as $name) {
									print_r("Articlename: ".$name["name"]);
								}

								echo "<br>";

								foreach ($OrderNumberById as $taxrate) {
									print_r("Taxrate: ".$taxrate["tax_rate"]);
								}

								echo "<br>";
								echo "<br>";*/



				
								/*$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><order></order>');
								



								$ordernumber = $xml->addChild('OrderNumber');
								$ordernumber->addChild('ordernumber', $FillOrderNumber);

								$orderid = $xml->addChild('OrderID');
								$orderid->addChild('orderid', $OrderNumberById['orderID']);

								$articleordernumber = $xml->addChild('Articleordernumber');
								$articleordernumber->addChild('articleordernumber', $OrderNumberById['articleordernumber']);

								$price = $xml->addChild('Price');
								$price->addChild('price', $OrderNumberById['price']);

								$quantity = $xml->addChild('Quantity');
								$quantity->addChild('quantity', $OrderNumberById['quantity']);

								$articlename = $xml->addChild('Articlename');
								$articlename->addChild('articlename', $OrderNumberById['name']);

								$tax = $xml->addChild('Tax');
								$tax->addChild('tax', $OrderNumberById['tax']);


								$MyDOM = dom_import_simplexml($xml)->ownerDocument; 
								$MyDOM->formatOutput = true; 
								$MyDOM->save('custom/plugins/MyPlugin/order.xml');
								// echo $xml->asXML();
								// $xml->asXML("custom/plugins/MyPlugin/MyTestXMLPlugin.xml");*/




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











































				/*$action = $args->get('subject');
				$request = $action->Request();

				
				echo '<pre>';
				die(var_dump(get_class_methods(get_class($request->getContext()))));
				echo '</pre>';

				echo '<pre>';
				print_r(get_class_methods(get_class($action->getOrderDetailsByOrderId(3))));
				echo '</pre>';*/