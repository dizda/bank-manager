<?php

namespace Dizda\BankManager\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    protected $dm;

    protected function getDm()
    {
        if(!$this->dm)
        {
            $this->dm = $this->get('doctrine.odm.mongodb.document_manager');
            return $this->dm;
        }else{
            return $this->dm;
        }
    }
    
    protected function getRepo($repository)
    {
        return $this->getDm()->getRepository($repository);
    }
}
