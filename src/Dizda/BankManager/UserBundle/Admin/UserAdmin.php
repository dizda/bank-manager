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


class UserAdmin extends Admin
{
    protected $userManager; /*injection du FOSUSER*/

    protected $formOptions = array(
        'validation_groups' => 'Profile'
    );

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('ws_login')
            //->add('accounts')
            ->add('enabled', 'boolean', array('editable' => true))
            ->add('locked', 'boolean', array('editable' => true))

        ;

//        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
//            $listMapper
//                ->add('impersonating', 'string', array('template' => 'DizsurfUserBundle:Admin:Field/impersonating.html.twig'))
//            ;
//        }
    }

    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('username')
            ->add('locked')
            ->add('email')
            ->add('id')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->add('ws_login')
            ->add('ws_password')
            ->add('plainPassword', 'text', array('required' => false))
            ->end()
            ->with('Options')
                ->add('pushover_user', null, array('required' => false))
                ->add('pushover_token', null, array('required' => false))
            ->end()
        /*->with('Groups')
            ->add('groups', 'sonata_type_model', array('required' => false))
        ->end()*/
            ->with('Management')
        //->add('roles', 'sonata_security_roles', array( 'multiple' => true, 'required' => false))
            ->add('accounts', null, array('required' => false))
            ->add('locked', null, array('required' => false))
            ->add('expired', null, array('required' => false))
            ->add('enabled', null, array('required' => false))
            ->add('credentialsExpired', 'checkbox', array('required' => false))
            ->end()
            ->with('Date')
            ->add('created_at', 'datetime', array('disabled' => true))
            ->add('last_login', 'datetime', array('disabled' => true))
            ->end()
            // ->with('Sites', array('collapsed' => true))
            //     ->add('sites', 'sonata_type_collection', array(), array(
            //     'edit' => 'inline',
            //     'inline' => 'table',
            //     'sortable'  => 'id'
            // ))
            // ->end()
        ;
    }

    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * @DI\InjectParams({
     *     "userManager" = @DI\Inject("fos_user.user_manager")
     * })
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function getUserManager()
    {
        if (!$this->userManager) {
            $this->userManager = $this->configurationPool->getContainer()->get('fos_user.user_manager');

        }
        return $this->userManager;

    }
}