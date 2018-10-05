<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Transaction;

class ProcessTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        //Find the transaction
        $transaction = Transaction::where('transaction_id' , $this->transaction_id)->first();

        //If the transaction is pending , get the transaction info again
        if ( $transaction->status === 'PENDING'){
            $soap = resolve('soap');
            $response = $soap->getTransactionInformation($transaction->transaction_id);
            if ($response->transactionState !== 'PENDING' ){
                //If the transaction has a different status, save it
                $transaction->status = $response->transactionState;
                $transaction->save();
            } else {
                //If the transaction is still pending, try again in ten minutes
                ProcessTransaction::dispatch($transaction->transaction_id)
                ->delay(now()->addMinutes(10));
            }
        }
    }
}
