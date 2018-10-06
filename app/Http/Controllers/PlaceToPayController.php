<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use App\Transaction;

class PlaceToPayController extends Controller
{
    /**
     * Create a new transaction
     * Validate the data and then consume the createTransaction from the web service
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validated = $request->validate([
            'medio' => 'required',
            'documentType' => 'required',
            'document' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'company' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'mobile' => 'required',
            'cuenta' => 'required',
            'bank' => 'required',
            'bankType' => 'required',
            'monto' => 'required',
        ]);
        $reference = str_random(32);
        $data = [
            'bankCode' => $validated['bank'],
            'bankInterface' => $validated['bankType'],
            'returnURL' => route('redirect'),
            'userAgent' => $request->header('User-Agent'),
            'reference' => $reference,
            'description' => $validated['description'],
            'totalAmount' => $validated['monto'],
            'ipAddress' => $request->ip(),
            'taxAmount' => 0,
            'devolutionBase' => 0,
            'tipAmount' => 0,
            //Person data
            'document' => $validated['document'],
            'documentType' => $validated['documentType'],
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'company' => $validated['company'],
            'emailAddress' => $validated['email'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'country' => $validated['country'],
            'phone' => $validated['phone'],
            'mobile' => $validated['mobile'],
        ];
        return resolve('soap')->createTransaction($data);
    }
    
    /**
     * Redirect to the home page
     * @return \Illuminate\Http\Response
     */
    public function welcome() 
    {
    return view('welcome')->with([
        'bankList' => resolve('soap')->getBankList()
    ]);
    }

    /**
     * Handle the redirect from the bank
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function redirect(Request $request){
    $token = $request->get('token');
    if (is_null($token)){
        return redirect(route('welcome'))->withErrors('Algo salio mal, intentalo de nuevo!'); 
    }
    $transaction = Transaction::where('token' , $token)->first();
    if (is_null($transaction)){
        return redirect(route('welcome'))->withErrors('Algo salio mal, intentalo de nuevo!'); 
    }
    $soap = resolve('soap');
    $response = $soap->getTransactionInformation($transaction->transaction_id);
    return view('welcomeBack', compact('transaction' , 'response'));
    }

    /**
     * Show all the transactions
     * @return \Illuminate\Http\Response
     */
    public function listar(){
    $transactions = Transaction::all();
    return view('listar', compact('transactions'));
    }
}

