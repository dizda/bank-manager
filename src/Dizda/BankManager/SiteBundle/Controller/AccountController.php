<?php

namespace Dizda\BankManager\SiteBundle\Controller;

use Dizda\BankManager\SiteBundle\Controller\CoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/account")
 */
class AccountController extends CoreController
{
    /**
     * @Route("/{account}")
     * @Template()
     */
    public function listAction($account)
    {
        $repo           = $this->getRepo('DizdaBankManagerCoreBundle:Transaction');

        $transactions   = $repo->findByMonth($account, new \DateTime());

        //$history        = $repo->compareLastMonths($account);





        return array('transactions' => $transactions,
                     //'history'      => $history,
                     'currentDate'  => new \DateTime(),
                     'previousMonth'=> (new \DateTime())->sub(new \DateInterval('P1M')),
                     'account'      => $account );
    }

    /**
     * @Route("/{account}/transaction/{year}/{month}")
     * @Template("DizdaBankManagerSiteBundle:Account:transactions.html.twig")
     */
    public function monthAction($account, $year, $month)
    {
        $repo           = $this->getRepo('DizdaBankManagerCoreBundle:Transaction');

        $date           = (new \DateTime())->setDate($year, $month, 1)->sub(new \DateInterval('P1M'));
        $previous       = clone $date;

        $transactions   = $repo->findByMonth($account, $date);


        return array('transactions' => $transactions,
                     'account'      => $account,
                     'previousMonth'=> $previous->sub(new \DateInterval('P1M')),
                     'currentDate'  => $date );
    }

    /**
     * @Route("/pointer/{transaction}")
     */
    public function pointerAction($transaction)
    {

        if ($this->getRequest()->isXmlHttpRequest()) {

            $repo          = $this->getRepo('DizdaBankManagerCoreBundle:Transaction');
            $transaction   = $repo->find($transaction);


            if (!$transaction->getDatePointer()) {

                $transaction->setDatePointer(new \DateTime());
                $response  = ['pointer' => true];

            } else {

                $transaction->setDatePointer(null);
                $response  = ['pointer' => false];

            }

            $this->getEm()->persist($transaction);
            $this->getEm()->flush();



            return new JsonResponse($response);

        }

    }

    /**
     * @Route("/exclude/{transaction}")
     */
    public function excludeAction($transaction)
    {

        if ($this->getRequest()->isXmlHttpRequest()) {

            $repo          = $this->getRepo('DizdaBankManagerCoreBundle:Transaction');
            $transaction   = $repo->find($transaction);


            if (!$transaction->getExcluded()) {

                $transaction->setExcluded(true);
                $response  = ['excluded' => true];

            } else {

                $transaction->setExcluded(false);
                $response  = ['excluded' => false];

            }

            $this->getEm()->persist($transaction);
            $this->getEm()->flush();



            return new JsonResponse($response);

        }

    }
}
