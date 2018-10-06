<?php

namespace App;

use SoapClient;

use App\Jobs\ProcessTransaction;
 
/**
 * Soap Helper class
 * Configured with the PTP specifications
 */
class SoapPTP
{
    /**
     * @var SoapClient
     * The SoapClient Connection
     */
    protected $soap;

    /**
     * @var Array
     * The authentication keys in the correct format
     */
    protected $auth;

    /**
     * @var 
     * The seed for hashing the keys
     */
    protected $seed;

    /**
     * Create a new class instance
     * Initialize the keys and create the SoapClient Object with the PTP data  
     */
    public function __construct(){
        //Get the seed
        $this->seed = date('c');

        //Get the keys from the env file
        $tranKey = env('PTP_TRAN_KEY');
        $login = env('PTP_LOGIN_KEY');
        $url = env('PTP_URL');

        //Make the key
        $key = sha1( $this->seed . $tranKey , false);
    
        $this->soap = new SoapClient($url);

        $this->auth = [
            'login' => $login,
            'tranKey' => $key,
            'seed' =>  $this->seed
        ];
    }

    /**
     * Get the bank list
     * Verifies if the bank list is cached, otherwise get the list from the web service and store it in cache
     * @return Object
     */
    public function getBankList(){
        if (cache()->has('BankList')){
            return cache('BankList');
        } else {
            $bankList = $this->soap->getBankList([
                'auth' => $this->auth
            ])
            ->getBankListResult->item;
            $expiresAt = now()->endOfDay();
            //Store the list in the cache for one day
            cache(['BankList' => $bankList] , $expiresAt);
            return $bankList;
        }
    }
    /**
     * Create a person array with the correct format
     * @param Array $data
     * @return Array
     */
    public function createPerson($data){
        return [
            'document' => $data['document'],
            'documentType' => $data['documentType'],
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'company' => $data['company'],
            'emailAddress' => $data['emailAddress'],
            'address' => $data['address'],
            'city' => $data['city'],
            'province' => $data['province'],
            'country' => $data['country'],
            'phone' => $data['phone'],
            'mobile' => $data['mobile'],
        ];
    }

    /**
     * Create a transaction
     * Make the request to create a transaction
     * @param Array $data
     * @return \Illuminate\Http\Response 
     */
    public function createTransaction($data){
        //Generate a random token
        $token = str_random(10);
        $response = $this->soap->createTransaction([
            'auth' => $this->auth,
            'transaction' => [
                'bankCode' => $data['bankCode'],
                'bankInterface' => $data['bankInterface'],
                'returnURL' => $data['returnURL']  .'?token=' . $token,
                'reference' => $data['reference'],
                'description' => $data['description'],
                'language' => 'es',
                'currency' => 'COP',
                'totalAmount' => $data['totalAmount'],
                'taxAmount' => $data['taxAmount'],
                'devolutionBase' => $data['devolutionBase'],
                'payer' => $this->createPerson($data),
                'buyer' => $this->createPerson($data),
                'shipping' => $this->createPerson($data),
                'ipAddress' => $data['ipAddress'],
                'userAgent' => $data['userAgent'],
            ]
        ])->createTransactionResult;
        //If the request was successfull, redirect to the bank
        if( $response->returnCode === 'SUCCESS'){
            $transaction = Transaction::create([
                'transaction_id' => $response->transactionID,
                'status' => 'PENDING',
                'token' => $token
            ]);
            ProcessTransaction::dispatch($transaction->transaction_id)
                    ->delay(now()->addMinutes(10));
    
            return redirect( $response->bankURL );
        //Otherwise redirect to back with errors
        } else {
            return back()->withErrors('Algo ha salido mal, intentalo de nuevo mas tarde');
        }
    
    }

    /**
     * Get the transaction information
     * Consume the web service getTransaction method
     * @param string $transaction_id
     * @return Object
     */
    public function getTransactionInformation($transaction_id){
        $response = $this->soap->getTransactionInformation([
            'auth' => $this->auth,
            'transactionID' => $transaction_id,
        ])->getTransactionInformationResult;
        return $response;
    }

    /**
     * Get the SoapClient Instance
     * @return SoapClient 
     */
    public function getSoap(){
        return $this->soap;
    }
}
