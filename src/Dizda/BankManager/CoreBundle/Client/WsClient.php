<?php
namespace Dizda\BankManager\CoreBundle\Client;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\Serializer\Serializer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Dizda\BankManager\CoreBundle\Event\AccountEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Dizda\BankManager\UserBundle\Document\User;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Service("dizda.bank.ws_client");
 */
class WsClient
{
    const URL_LOGIN             = 'https://mobile.creditmutuel.fr/wsmobile/fr/IDE.html';        // POST
    const URL_GET_ACCOUNTS      = 'https://mobile.creditmutuel.fr/wsmobile/fr/PRC.html';        // POST
    const URL_LIST_OPERATIONS   = 'https://mobile.creditmutuel.fr/wsmobile/fr/LSTMVT.html';     // POST
    const COOKIES_FILE          = '/tmp/cookie.txt';

    private $curl;
    private $serializer;
    private $dispatcher;
    private $postLogin          = array( '_media'        => 'AN', // +1
                                         '_cm_user'      => null,
                                         '_cm_pwd'       => null,
                                         '_appversion'   => '2.0.4',
                                         '_cible'        => 'CM',
                                         '_wsversion'    => '2' ); // +1

    private $options            = array( CURLOPT_HEADER          => true,
                                         CURLOPT_RETURNTRANSFER  => true,
                                         CURLOPT_FOLLOWLOCATION  => true,
                                         CURLOPT_USERAGENT       => 'AndroidVersion:4.0.4',
                                         CURLOPT_POST            => true,
                                         CURLOPT_COOKIEFILE      => self::COOKIES_FILE,
                                         CURLOPT_COOKIEJAR       => self::COOKIES_FILE );
    private $account            = [];
    private $transactions       = [];


    /**
     * @DI\InjectParams({
     *     "serializer" = @DI\Inject("serializer"),
     *     "dispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(Serializer $serializer, EventDispatcher $dispatcher)
    {
        $this->curl         = curl_init();
        $this->serializer   = $serializer;
        $this->dispatcher   = $dispatcher;
        
        curl_setopt_array($this->curl, $this->options);
        
        
    }
    
    public function login($user, $pass)
    {
        $this->postLogin['_cm_user'] = $user;
        $this->postLogin['_cm_pwd']  = $pass;
        
        curl_setopt($this->curl, CURLOPT_URL, self::URL_LOGIN);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postLogin);
        
        curl_exec($this->curl);
        
        if(curl_getinfo($this->curl, CURLINFO_HTTP_CODE) == 200)
            return true;
        else
            return false;
    }
    
    public function getAccounts()
    {
        $this->postLogin = array( '_media'        => 'AN',
                                  '_wsversion'    => '2' );  // overwrite the postfields, delete logins informations like android does
       
        
        $content = $this->execCurlParseXML(self::URL_GET_ACCOUNTS);
        
        foreach($content->liste_compte->compte as $account)
        {
            $this->account[(string) $account->iban] = $this->serializer->deserialize($account->asXML(), 'Dizda\BankManager\CoreBundle\Document\Account', 'xml');
        }
        
        
        $this->dispatcher->dispatch('dizda.bank.account.update', new AccountEvent(AccountEvent::ACCOUNTS, $this->account));
        
        return true;
    }

    
    
    /* get transactions for each accounts */
    public function getTransactions(User $user)
    {
        $this->postLogin = array( '_media'        => 'AN',
                                  '_wsversion'    => '1',
                                  'Devise'        => 'EUR' );

        foreach($this->account as $account)
        {
            $this->postLogin['IBAN'] = $account->getIban();
            
            $transactions = $this->execCurlParseXML(self::URL_LIST_OPERATIONS);
            //die(var_dump($transactions));
            foreach($transactions->tabmvt->ligmvt as $transaction)
            {
                $this->transactions[$account->getIban()][] = $this->serializer->deserialize($transaction->asXML(), 'Dizda\BankManager\CoreBundle\Document\Transaction', 'xml');
            }
            
        }
        
        $this->dispatcher->dispatch('dizda.bank.transaction.add', new AccountEvent(AccountEvent::TRANSACTIONS, $this->transactions, $user));
        
        return true;
    }
    
    /**
     * Exec cURL and transform the response into XML
     * @param type $curl
     * @return \SimpleXMLElement
     */
    private function execCurlParseXML($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postLogin);
        
        //$content = explode("\n", curl_exec($this->curl));
        $content = curl_exec($this->curl);
        $content = substr($content, strpos($content, '<?xml'));
        
        $content = new \SimpleXMLElement($content);
        
        return $content;
    }
    
    public function __destruct() {
        curl_close($this->curl);

        $file = new Filesystem();
        $file->remove(static::COOKIES_FILE); /* we remove the cookies file, from the last session */
    }
    
}