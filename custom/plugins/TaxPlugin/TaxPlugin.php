﻿<?php

namespace TaxPlugin;

use Shopware\Bundle\AttributeBundle\Service\TypeMapping;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Models\Customer\Customer;
use Shopware\Models\Customer\Address;


class TaxPlugin extends Plugin
{

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Modules_Admin_SaveRegister_Successful' => 'fetchVatId',
            'Enlight_Controller_Action_PreDispatch_Frontend_Account' => 'insertVatId',
        ];
    }


    public function install(InstallContext $context)
    {
        $crud = $this->container->get('shopware_attribute.crud_service');
        $crud->update('s_user_attributes', 'vatid',TypeMapping::TYPE_TEXT);
        $metaDataCache = Shopware()->Models()->getConfiguration()->getMetadataCacheImpl();
        $metaDataCache->deleteAll();
        Shopware()->Models()->generateAttributeModels(['s_user_attributes']);
    }


    public function uninstall(UninstallContext $context)
    {
        $crud = $this->container->get('shopware_attribute.crud_service');
        $crud->delete('s_user_attributes', 'vatid');
        Shopware()->Models()->generateAttributeModels(['s_user_attributes']);
    }


    public function activate(ActivateContext $context)
    {
        $em  = $this->container->get('models');
        $customergroup = $em->getRepository(Customer::class)->findBy(['group' => 'H']);

        if($customergroup) {
            $vatId = $customergroup->getDefaultBillingAddress()->getVatId();
            $customergroup->getAttribute()->setVatid($vatId);
        }
        $em->persist($customergroup);
        $em->flush();
    }


    public function insertVatId(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->get('subject');
        $request = $controller->Request();
        $view = $controller->View();
        $service = $controller->get('shopware_attribute.data_loader');
        $userData = Shopware()->Modules()->Admin()->sGetUserData();
        $view->addTemplateDir(__DIR__ . '/Resources/views/');

        if($request->getActionName() === 'profile')
        {
            $userID = $userData['additional']['user']['userID'];
            $data = $service->load('s_user_attributes', $userID);
            if(!empty($data))
            {
                $view->assign('vatId', $data['vatid']);
            }
        }
    }


    public function fetchVatId(\Enlight_Event_EventArgs $args)
    {
        $em = $this->container->get('models');
        /** @var Customer $customer */
        $customer = $em->getRepository(Customer::class)->find($args->id);

        if($customer)
        {
            $vatId = $customer->getDefaultBillingAddress()->getVatId();
            $customer->getAttribute()->setVatid($vatId);
            $customer->getDefaultBillingAddress()->setVatId(null);
        }
        $em->persist($customer);
        $em->flush();
    }



//<?php
//
//namespace TaxPlugin;
//
//use function MongoDB\BSON\fromJSON;
//use Shopware\Components\Plugin;
//use Shopware\Components\Plugin\Context\InstallContext;
//use Shopware\Components\Plugin\Context\UninstallContext;
//use Shopware\Models\Customer\Customer;
//use League\Flysystem\Adapter\Local;
//use Shopware\Bundle\AccountBundle\Form\Account\EmailUpdateFormType;
//use Shopware\Bundle\AccountBundle\Form\Account\PasswordUpdateFormType;
//use Shopware\Bundle\AccountBundle\Form\Account\ProfileUpdateFormType;
//use Shopware\Bundle\AccountBundle\Form\Account\ResetPasswordFormType;
//use Shopware\Models\Customer\Address;
//
//
//
//class TaxPlugin extends Plugin
//{
//
//    public static function getSubscribedEvents()
//    {
//        return [
////           'Enlight_Controller_Action_PostDispatchSecure_Frontend_Register' => 'fetchVatId1',
//            'Shopware_Modules_Admin_SaveRegister_Successful' => 'fetchVatId2',
//            //'Enlight_Controller_Action_PreDispatch_Frontend_Account' => 'insertVatId1',  // 1.Beispiel
//            //'Enlight_Controller_Action_PreDispatch_Frontend_Account' => 'insertVatId2'     // 2.Beispiel
//        ];
//    }
//
//    public function install(InstallContext $context)
//    {
//
//    }
//
//
//    public function uninstall(UninstallContext $context)
//    {
//        parent::uninstall($context); // TODO: Change the autogenerated stub
//    }
//
//
//    public function fetchVatId1(\Enlight_Event_EventArgs $args)
//    {
//        $controller = $args->get('subject');
//        $request = $controller->Request();
//        $data = $request->getPost(['register']['billing']);
//
//        if($request->getActionName() === 'saveRegister')
//        {
//            if(!empty($data))
//            {
//                $MyVatId = $data['register']['billing']['vatId'];
//            } else {
//                die('data failure');
//            }
//        }
//
//        return $MyVatId;
//    }
//
//    // 1.Beispiel
//
//    public function insertVatId1(\Enlight_Event_EventArgs $args)
//    {
//        $controller = $args->get('subject');
//        $request = $controller->Request();
//        $view = $controller->View();
//        $service = $controller->get('shopware_attribute.data_loader');
//        $userData = Shopware()->Modules()->Admin()->sGetUserData();
//        $view->addTemplateDir(__DIR__ .'/Resources/views/');
//
//
//
//        if($request->getActionName() === 'profile')
//        {
//            $userID = $userData['additional']['user']['userID'];
//            $data = $service->load('s_user_attributes', $userID);
//            if(!empty($data))
//            {
//                $view->assign('vatId', $data['vatid']);
//            }
//        }
//    }
//
//    // 2.Beispiel
//
//    public function insertVatId2(\Enlight_Event_EventArgs $args)
//    {
//        $controller = $args->get('subject');
//        $request = $controller->Request();
//        $view = $controller->View();
//        $em = $this->container->get('models');
//        $view->addTemplateDir(__DIR__ .'/Resources/views/');
//
//        $userId = $controller->get('session')->get('sUserId');
//        $customer = $em->find(Customer::class, $userId);
//        $vatId = $customer->getAttribute()->getVatid();
//        $view->assign('vatId', $vatId);
//
//        if($request->getActionName() === 'saveProfile')
//        {
//            if($request->getParam('asd'))
//            {
//                if($customer)
//                {
//                    $customer->getAttribute()->setVatid($request->getParam('asd'));
//                }
//                $em->persist($customer);
//                $em->flush();
//            }
//        }
//    }
//
//
//    public function fetchVatId2(\Enlight_Event_EventArgs $args)
//    {
//        $em = $this->container->get('models');
//
//        /** @var Customer $customer */
//        $customer = $em->getRepository(Customer::class)->find($args->id);
//
//        if($customer)
//        {
//            $vatId = $customer->getDefaultBillingAddress()->getVatId();
//            $customer->getAttribute()->setVatid($vatId);
//            $customer->getDefaultBillingAddress()->setVatId(null);
//        }
//
//        $em->persist($customer);
//        $em->flush();
//    }



//{extends file="parent:frontend/account/profile.tpl"}
//
//{$smarty.block.parent}
//
//{block name="frontend_account_profile_profile_body"}
//
//
//
//{* VatId *}
//{block name="frontend_account_profile_profile_input_vatid"}
//<div class="profile--vatid">
//                <input name="asd"   /* 1. Beispiel: profile[attribute][vatid] Quelle://engine/Shopware/Bundle/AccountBundle/Form/Account/ProfileUpdateFormType.php */
//                       type="text"
//                       required="required"
//                       aria-required="true"
//                       placeholder="{s name='RegisterPlaceholderVatId' namespace="frontend/register/personal_fieldset"}{/s}"
//                       value="{$vatId}"
//                       class="profile--field is--required{if $errorFlags.vatid} has--error{/if}"
///>
//            </div>
//        {/block}
//{/block}




    // public function insertVatId(\Enlight_Event_EventArgs $args)
    // {
    //$controller = $args->get('subject');
    //$request = $controller->Request();
    //$data = $request->getPost(['register']['billing']);
    //$em = $this->container->get('models');

    //  /** @var Customer $customer */
    //$customer = $em->getRepository(Customer::class)->find(40);


//        $db = Shopware()->Db();
//        $getRepository = $em->getRepository(Customer::class)->findAll();

//             if($request->getActionName() === 'saveRegister') {
//                 if (!empty($data)) {
//                     $vatId = $data['register']['billing']['vatId'];
//                     echo '<pre>';
//                     var_dump(get_class_methods(get_class($getRepository)));
//                     echo '</pre>';
    //$sql = 'INSERT INTO s_user_attributes(vatid) VALUES ('.$vatId.')';
    //$db->query($sql);
//                 }
//             }

    //}

}