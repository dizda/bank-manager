<?php
namespace Dizda\BankManager\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class ExportToMysqlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dizda:bankmanager:export')
            ->setDescription('Fetch new accounts/transactions from the bank service\'s.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $mongoAccount = $dm->getRepository('DizdaBankManagerCoreBundle:Account')->findAll();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($mongoAccount as $account) {
            $output->writeln('<info>- '.$account->getIban().'</info> ('.$account->getBalanceHistory()->count().' balances-history) ('.$account->getTransactions()->count().' transactions)');
            $mysqlAccount = $em->getRepository('DizdaBankManagerCoreBundle:Account')->find($account->getIban());

            if ($mysqlAccount) {

                $mysqlAccount->setBalance($account->getBalance());

                $cptBalance = 0;
                $cptTrans   = 0;

                foreach ($account->getBalanceHistory() as $mongoBalance) {
                    $mysqlBalance = new \Dizda\BankManager\CoreBundle\Entity\AccountBalanceHistory();
                    $mysqlBalance->setAccount($mysqlAccount);
                    $mysqlBalance->setDateFetched($mongoBalance->getDateFetched());
                    $mysqlBalance->setBalance($mongoBalance->getBalance());

                    $em->persist($mysqlBalance);

                    $cptBalance++;
                }

                $output->writeln('<comment>'.$cptBalance.' balance writed.</comment>');

                foreach ($account->getTransactions() as $mongoTrans) {
                    $mysqlTrans = new \Dizda\BankManager\CoreBundle\Entity\Transaction();
                    $mysqlTrans->setId($mongoTrans->getId());
                    $mysqlTrans->setAccount($mysqlAccount);
                    $mysqlTrans->setAmount($mongoTrans->getAmount());
                    $mysqlTrans->setDateTransaction($mongoTrans->getDateTransaction());
                    $mysqlTrans->setDateFetched($mongoTrans->getDateFetched());
                    $mysqlTrans->setDatePointer($mongoTrans->getDatePointer());
                    $mysqlTrans->setExcluded($mongoTrans->getExcluded());
                    $mysqlTrans->setLabel($mongoTrans->getLabel());
                    $mysqlTrans->setLabel2($mongoTrans->getLabel2());

                    $em->persist($mysqlTrans);

                    $cptTrans++;
                }

                $em->persist($mysqlAccount);
                $output->writeln('<comment>'.$cptTrans.' transactions writed.</comment>');

            }


        }

        $em->flush();

        $output->writeln('All ok.');

    }
}