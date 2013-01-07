<?php

namespace Dizda\BankManager\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Security\Acl\Permission\MaskBuilder;
use JMS\DiExtraBundle\Annotation as DI;

use FOS\UserBundle\Model\UserManagerInterface;


class OptionsAdmin extends Admin
{


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('alert_amount')
            ->add('last_update')

        ;

//        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
//            $listMapper
//                ->add('impersonating', 'string', array('template' => 'DizsurfUserBundle:Admin:Field/impersonating.html.twig'))
//            ;
//        }
    }


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('alert_amount')
            ->add('last_update')
            ->end()

        ;
    }

}