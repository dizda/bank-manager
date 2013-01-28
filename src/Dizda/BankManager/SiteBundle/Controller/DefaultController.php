<?php

namespace Dizda\BankManager\SiteBundle\Controller;

use Dizda\BankManager\SiteBundle\Controller\CoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends CoreController
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    public function menuAction()
    {
    	$accounts  = $this->getRepo('DizdaBankManagerCoreBundle:Account')
                          ->findByUser($this->getUser());

        return $this->render('DizdaBankManagerSiteBundle::menu.html.twig', [ 'accounts' => $accounts ]);
    }
}
