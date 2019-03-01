<?php


namespace MyTest;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

class MyTest extends Plugin {

	public static function getSubscribedEvents() {
		return [
			'Shopware_Modules_Order_SendMail_Send' => 'MyTest'
		];
	}

	public function MyTest(\Enlight_Event_EventArgs $args) {

		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><order></order>');
		$xml->addAttribute('version', '1.0');
		
		$ordernumber = $xml->addChild('OrderNumber');
		$ordernumber->addChild('ordernumber', '20176');

		$orderid = $xml->addChild('OrderID');
		$orderid->addChild('orderid', '3');

		$articleid = $xml->addChild('ArticleID');
		$articleid->addChild('articleid', '3');

		$articleordernumber = $xml->addChild('Articleordernumber');
		$articleordernumber->addChild('articleordernumber', 'BS_01');

		$articleordernumber = $xml->addChild('Articleordernumber');
		$articleordernumber->addChild('articleordernumber', 'BS_01');

		$price = $xml->addChild('Price');
		$price->addChild('price', '15');

		$quantity = $xml->addChild('Quantity');
		$quantity->addChild('quantity', '1');

		$articlename = $xml->addChild('Articlename');
		$articlename->addChild('articlename', 'SLIM FIT JEANS FÜR DAMEN 31 inch');

		$tax = $xml->addChild('Tax');
		$tax->addChild('tax', '19');


		echo $xml->asXML();
		$xml->asXML("custom/plugins/MyTest/MyTestXML.xml");
	}
}
