<?php

namespace Dizda\BankManager\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    protected $em;

    protected function getEm()
    {
        if(!$this->em)
        {
            $this->em = $this->get('doctrine.orm.entity_manager');
            return $this->em;
        }else{
            return $this->em;
        }
    }
    
    protected function getRepo($repository)
    {
        return $this->getEm()->getRepository($repository);
    }

}
