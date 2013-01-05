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
            ->setName('bankmanager:fetch')
            ->setDescription('Fetch new accounts/transactions from the bank service\'s.')
            /*->addArgument('login', InputArgument::OPTIONAL, 'Your login')
            ->addArgument('pwd', InputArgument::OPTIONAL, 'Your password')*/
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getContainer()->get('dizda.bank.ws_client');
        $users  = $this->getContainer()->get('fos_user.user_manager')->findUsers();


        foreach($users as $user)
        {

            /* If login / pass aren't specified, we ignore and fetch the next user */
            if($user->getWsLogin() === null || $user->getWsPassword() === null)
            {
                $output->writeln('<comment>'.$user->getUsername().' : You have to specify your service login/pass</comment>, <info>ignoring</info>');
                continue;
            }

            /* If client can't connect with informations given, we ignore and fetch the next user */
            if(!$client->login($user->getWsLogin(), $user->getWsPassword()))
            {
                $output->writeln('<comment>'.$user->getUsername().' : Can\'t connect to the webservice, credentials incorrect ?</comment>, <info>ignoring</info>');
                continue;
            }

            /* Otherwise, we store accounts & transactions */
            if($client->getAccounts() && $client->getTransactions())
            {
                $accounts = $this->getContainer()->get('dizda.bank.listener.account')->getStatsFetched();

                foreach ($accounts as $iban => $account) {
                    $output->writeln('<comment>'.$user->getUsername().'</comment> : Account <comment>'.$iban.'</comment> <info>saved</info> ('.$account['count'].' fetched/'.$account['added'].' added).');
                }

                $output->writeln('<comment>'.$user->getUsername().'</comment> : Finished.');
            }

        }

        $output->writeln('All ok.');

    }
}