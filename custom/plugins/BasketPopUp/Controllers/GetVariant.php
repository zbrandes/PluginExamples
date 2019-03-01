<?php

namespace BasketPopUp\Controllers;


class GetVariant extends \Enlight_Controller_Action
{

    public function init()
    {
        $this->View()->loadTemplate('frontend/get_variant/index.tpl');
    }


	public function indexAction()
	{
		if(isset($_POST['formData']))
		{
			$data = json_decode($_POST['formData']);
			$VariantData = null;
                        $articleID = Shopware()->Modules()->Articles()->sGetArticleIdByOrderNumber($_POST['artNr']);
                        //$categoryId = $this->Request()->get('sCategory');

                         $selection = [];
			foreach($data as $o)
			{
				if($o->name != '__csrf_token')
                                {
				    /*for ($i = 0; $i < count($o->name); $i++)
				    {
					$groupName = $o->name;
					for ($j = 0; $j < count($o->value); $j++)
					{
					    $groupValue = $o->value;
					    $VariantData = Shopware()->Modules()->Articles()->sGetArticleById($articleID, $categoryId ,$_POST['artNr'], [$groupName[$i] => $groupValue[$i]]);
					    $_POST['artNr'] = $VariantData['ordernumber'];
					    $OrderNumber = $_POST['artNr'];
					}
				    }*/
                                      $selection[str_replace(['group[',']'], ['',''], $o->name)] = $o->value;
				}
			}
            $VariantData = Shopware()->Modules()->Articles()->sGetArticleById(
                (int)$articleID,
                null,
                $_POST['artNr'],
                $selection
            );
            echo $VariantData['ordernumber'];
		}
	}
}


//if($o->name != '__csrf_token')
//{
//    if($o->name == "group[5]" && $o->value)
//    {
//        $group5 = $o->value;
//    }
//    if($o->name == "group[6]" && $o->value)
//    {
//        $group6 = $o->value;
//    }
//    if($o->name == "group[7]" && $o->value)
//    {
//        $group7 = $o->value;
//    }
//    $variantData[str_replace(['group[',']'], ['',''], $o->name)] = $o->value;
//    $VariantData = Shopware()->Modules()->Articles()->sGetArticleById($articleID, $categoryId ,$_POST['artNr'], [5 => $group5, 6 => $group6], 7 => $group7);
//                        $_POST['artNr'] = $VariantData['ordernumber'];
//                        $OrderNumber = $_POST['artNr'];
//				}
