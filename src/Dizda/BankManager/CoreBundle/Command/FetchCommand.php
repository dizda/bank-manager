<?php
namespace Dizda\BankManager\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dizda\BankManager\CoreBundle\Client\WsClient;

class FetchCommand extends ContainerAwareCommand
{
    /**
     *  Thème boostrap qui irait grave bien :
     *  https://wrapbootstrap.com/theme/unicorn-admin-template-WB0F35928
     */
    protected function configure()
    {
        $this
            ->setName('creditmutuel:fetch')
            ->setDescription('Fetch new accounts/transactions from the CréditMutuel service.')
            ->addArgument('login', InputArgument::OPTIONAL, 'Your login')
            ->addArgument('pwd', InputArgument::OPTIONAL, 'Your password')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getContainer()->get('dizda.bank.ws_client');

        $login = $input->getArgument('login');
        $pwd   = $input->getArgument('pwd');


        if($login === null || $pwd === null)
            throw new \Exception('You have to specify your service login/pass');

        if(!$client->login($login, $pwd))
            throw new \Exception('Can\'t connect to the webservice, credentials incorrect ?');
        
        if($client->getAccounts() && $client->getTransactions())
        {
            $accounts = $this->getContainer()->get('dizda.bank.listener.account')->getStatsFetched();
        
            foreach ($accounts as $iban => $account) {
                $output->writeln('Account <comment>'.$iban.'</comment> <info>saved</info> ('.$account['count'].' fetched/'.$account['added'].' added).');
            }
            
            $output->writeln('Finished.');
        }

    }
}