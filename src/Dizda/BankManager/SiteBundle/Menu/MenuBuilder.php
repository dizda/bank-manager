<?php
namespace Dizda\BankManager\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @Service("dizda.bank.menu.builder");
 */
class MenuBuilder
{
    private $factory;

    /**
     * @DI\InjectParams({
     *     "factory"         = @DI\Inject("knp_menu.factory")
     * })
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'dizda_bankmanager_site_default_index'));
        // ... add more children

        return $menu;
    }
}