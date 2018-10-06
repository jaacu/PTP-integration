<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\SoapPTP;
use SoapClient;

class PTPTest extends TestCase
{
    /**
     * Get the Soap helper class
     * @return \App\SoapPTP
     */
    public function getSoapHelper()
    {
        return resolve('soap');
    }

    /**
     * Test that the getSoapHelper method works
     * @return void
     */
    public function testGetSoapHelperWorks()
    {
        $soap = $this->getSoapHelper();
        $this->assertInstanceOf(SoapPTP::class , $soap);
        $this->assertInstanceOf(SoapClient::class , $soap->getSoap());
    }

    /**
     * Tests that the getBankList methop works
     * Assert that the method returns the list and that the list is cached
     * @return void
     */
    public function testGetBankListWorks()
    {
        $soap = $this->getSoapHelper();
        $banks = $soap->getBankList();

        $this->assertTrue( is_array($banks) );
        $this->assertObjectHasAttribute( 'bankCode', $banks[0] );
        $this->assertObjectHasAttribute( 'bankName', $banks[0] );
        $this->assertTrue(cache()->has('BankList'));

    }

    /**
     * Test that the welcome view works
     * @return void
     */
    public function testWelcomeViewWorks(){
        $response = $this->get(route('welcome'));
        $response->assertOk();
    }

    /**
     * Test the redirect view without the token redirect to the welcome page
     * @return void
     */
    public function testRedirectWithoutTokenWorks(){
        $response = $this->get(route('redirect'));
        $response->assertRedirect(route('welcome'));

    }
    
    /**
     * Test the redirect view with the token shows the correct info
     * @return void
     */
    public function testRedirectWithTokenWorks(){
        $transaction = \App\Transaction::first();
        if( is_null($transaction)) return;
        $response = $this->get(route('redirect') . '?token=' . $transaction->token);
        $response->assertOk();
        $response->assertSee($transaction->status);
    }
}
