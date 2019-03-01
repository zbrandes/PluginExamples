<?php

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><EBShop><Order></Order></EBShop>');

$xml->addAttribute('version', '1.0');

$orderdata = $xml->addChild('Oderdata');
$orderdata->addChild('OrderNumber', '20176');
$orderdata->addChild('OrderID', '3');



$basketdata = $xml->addChild('Basketdata');
$basketdata->addChild('Articlenumber', 'BS_01');
$basketdata->addChild('Articlename', 'BOOTCUT JEANS FÜR DAMEN inch31');



$customerdata = $xml->addChild('Customerdata');
$customerdata->addChild('Firstname', 'Evgenij');
$customerdata->addChild('Lastname', 'Brandes');
$customerdata->addChild('Street', 'Lembergweg');
$customerdata->addChild('Zipcode', '71067');
$customerdata->addChild('City', 'Sindelfingen');



$shippingdata = $xml->addChild('Shippingdata');
$shippingdata->addChild('Firstname', 'Evgenij');
$shippingdata->addChild('Lastname', 'Brandes');
$shippingdata->addChild('Street', 'Lembergweg');
$shippingdata->addChild('Zipcode', '71067');
$shippingdata->addChild('City', 'Sindelfingen');



$paymentdata = $xml->addChild('Paymentdata');
$paymentdata->addChild('Name', 'DebitPayment');
$paymentdata->addChild('Description', 'Maestro');



$costdata = $xml->addChild('Costdata');
$costdata->addChild('ShiipingCost', '3.00');
$costdata->addChild('Price', '15.00');
$costdata->addChild('NetPrice', '13.79');
$costdata->addChild('Quantity', '1');
$costdata->addChild('Tax', '19');



$xmlDOM = dom_import_simplexml($xml)->ownerDocument;
$xmlDOM->formatOutput = true;
$xmlDOM->save('lol.xml');

?>