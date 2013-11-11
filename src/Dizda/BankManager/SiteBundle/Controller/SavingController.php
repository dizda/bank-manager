<?php

namespace Dizda\BankManager\SiteBundle\Controller;

use Dizda\BankManager\SiteBundle\Controller\CoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/saving")
 */
class SavingController extends CoreController
{
    /**
     * @Route("/history")
     * @Template()
     */
    public function historyAction()
    {
        $repo    = $this->getRepo('DizdaBankManagerCoreBundle:Transaction');
        $history = $repo->getSavingHistory('FR7610278060550002013720614');

        return array('histories' => $history);
    }

}
