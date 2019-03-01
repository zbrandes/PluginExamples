<?php

namespace BasketPopUp;


use Shopware\Components\Plugin;
use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;
use Shopware\Models\Customer\Customer;
use Shopware\Components\Model\QueryBuilder;
use Shopware\Components\Model\ModelRepository;
use Shopware\Models\Shop\Shop;
use BasketPopUp\Controllers\GetVariant;



class BasketPopUp extends Plugin
{

    public static function getSubscribedEvents()
    {
        return [
            //'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'BasketPopUp'
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Checkout' => 'BasketPopUp',
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'BasketPopUpButton',
            'Shopware_Modules_Basket_AddArticle_Start' => 'addArticleStart',
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_GetVariant' => 'onRegisterController',
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch',
            'Enlight_Controller_Action_PreDispatch_Frontend' => 'onPreDispatch'
        ];
    }

    public function onPostDispatch(\Enlight_Event_EventArgs $args)
    {
        $args->get('subject')->View()->addTemplateDir(__DIR__.'/Resources/views/');
    }

    public function onPreDispatch(\Enlight_Event_EventArgs $args)
    {
        $args->get('subject')->View()->addTemplateDir(__DIR__.'/Resources/views/');
    }

    public function getBasketConfiguratorSet($basketId)
    {
        $connection = $this->container->get('dbal_connection');
        $builder = $connection->createQueryBuilder();
        $builder->select('articles.configurator_set_id')
            ->from('s_articles', 'articles')
            ->innerJoin('articles', 's_articles_details', 'details', 'articles.id = details.articleID')
            ->where('details.ordernumber = :ordernumber')
            ->setParameter('ordernumber', $basketId);

        $statusVariant = $builder->execute()->fetch();
        return $statusVariant['configurator_set_id'];
    }

    public function BasketPopUp(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->get('subject');
        $request = $controller->Request();
        $view = $controller->View();
        $view->addTemplateDir(__DIR__ . '/Resources/views/');


        $ordernumber = $request->getParam('sAdd');  // $basketId = $request->getPost()['sAdd'];
        $sQuantity = $request->setParam('sQuantity');
        $categoryId = $request->get('sCategory');
        $selection = $request->getParam('group', []);


        if($request->getActionName() == 'ajax_add_article')    // beim Event von PreDispatch: addArticle
        {
                if($this->getBasketConfiguratorSet($ordernumber) != null)
                {
                    $articleID = Shopware()->Modules()->Articles()->sGetArticleIdByOrderNumber($ordernumber);
                    $ArticleData = Shopware()->Modules()->Articles()->sGetArticleById($articleID, $categoryId, $ordernumber, $selection);
                    $view->assign('statusArticle', 'hasVariant');
                    $view->assign('quantity', $sQuantity);
                    $view->assign('ordernumber', $ordernumber);
                    $view->assign('sArticle', $ArticleData);
                } else {
                    $view->assign('statusArticle', 'NoVariant');
                }
        }
    }


    public function BasketPopUpButton(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->get('subject');
        $view = $controller->View();
        $view->addTemplateDir(__DIR__ . '/Resources/views/');
    }

    public function addArticleStart(\Enlight_Event_EventArgs $args)
    {
        $basketId = $args->id;

        if($basketId)
        {
            if($this->getBasketConfiguratorSet($basketId) != null && $_POST['preventVariant'] != 'true')
            {
                 return false;
            }
        }
    }

    public function onRegisterController(\Enlight_Event_EventArgs $args)
    {
        return GetVariant::class;
    }
}