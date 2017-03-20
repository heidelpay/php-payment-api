<?php
namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\PaymentMethods\DirectDebitPaymentMethod as  DirectDebit;

/**
 *
 *  Direct debit Test
 *
 *  Connection tests can fail due to network issues and scheduled downtime.
 *  This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category UnitTest
 */
class DirectDebitPaymentMethodTest extends TestCase
{
    /**
     * SecuritySender
     *
     * @var string SecuritySender
     */
    protected $SecuritySender = '31HA07BC8142C5A171745D00AD63D182';
    /**
     * UserLogin
     *
     * @var string UserLogin
     */
    protected $UserLogin      = '31ha07bc8142c5a171744e5aef11ffd3';
    /**
     * UserPassword
     *
     * @var string UserPassword
     */
    protected $UserPassword   = '93167DE7';
    /**
     * TransactionChannel
     *
     * @var string TransactionChannel
     */
    protected $TransactionChannel = '31HA07BC8142C5A171749A60D979B6E4';
    /**
     * SandboxRequest
     *
     * Request will be send to Heidelpay sandbox payment system.
     *
     * @var string
     */
    protected $SandboxRequest = true;
    
    /**
     * Customer given name
     *
     * @var string nameGiven
     */
    protected $nameGiven = 'Heidel';
    /**
     * Customer family name
     *
     * @var string nameFamily
     */
    protected $nameFamily ='Berger-Payment';
    /**
     * Customer company name
     *
     * @var string nameCompany
     */
    protected $nameCompany = 'DevHeidelpay';
    /**
     * Customer id
     *
     * @var string shopperId
     */
    protected $shopperId = '12344';
    /**
     * customer billing address street
     *
     * @var string addressStreet
     */
    protected $addressStreet = 'Vagerowstr. 18';
    /**
     * customer billing address state
     *
     * @var string addressState
     */
    protected $addressState  = 'DE-BW';
    /**
     * customer billing address zip
     *
     * @var string addressZip
     */
    protected $addressZip    = '69115';
    /**
     * customer billing address city
     *
     * @var string addressCity
     */
    protected $addressCity    = 'Heidelberg';
    /**
     * customer billing address city
     *
     * @var string addressCity
     */
    protected $addressCountry = 'DE';
    /**
     * customer mail address
     *
     * @var string contactMail
     */
    protected $contactMail = "development@heidelpay.de";
    
    /**
     * customer mail address
     *
     * @var string contactMail
     */
    protected $iban = "DE89370400440532013000";
    
    /**
     * customer mail address
     *
     * @var string contactMail
     */
    protected $holder = "Heidel Berger-Payment";
    
    /**
     * Transaction currency
     *
     * @var string currency
     */
    protected $currency = 'EUR';
    /**
     * Secret
     *
     * The secret will be used to generate a hash using
     * transaction id + secret. This hash can be used to
     * verify the the payment response. Can be used for
     * brute force protection.
     *
     * @var string secret
     */
    protected $secret = 'Heidelpay-PhpApi';
    
    /**
     * PaymentObject
     *
     * @var \Heidelpay\PhpApi\PaymentMethods\SofortPaymentMethod
     */
    protected $paymentObject = null;

    /**
     * Constructor used to set timezone to utc
     */
  public function __construct()
  {
      date_default_timezone_set('UTC');
  }

  /**
   * Set up function will create a direct debit object for each testcase
   *
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp()
  {
      $DirectDebit = new DirectDebit();
    
      $DirectDebit->getRequest()->authentification($this->SecuritySender, $this->UserLogin, $this->UserPassword, $this->TransactionChannel, 'TRUE');
    
      $DirectDebit->getRequest()->customerAddress($this->nameGiven, $this->nameFamily, null, $this->shopperId, $this->addressStreet, $this->addressState, $this->addressZip, $this->addressCity, $this->addressCountry, $this->contactMail);
    
    
      $DirectDebit->_dryRun=true;
    
      $this->paymentObject = $DirectDebit;
  }
  
  /**
   * Get current called method, without namespace
   *
   * @param string $method
   *
   * @return string class and method
   */
  public function getMethod($method)
  {
      return substr(strrchr($method, '\\'), 1);
  }
    
  /**
   * Test case for a single direct debit authorize
   *
   * @return string payment reference id for the direct debit transaction
   * @group connectionTest
   * @test
   */
  public function Authorize()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
      $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');
      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

      $this->paymentObject->setAdapter('\Heidelpay\PhpApi\Adapter\CurlAdapter');
      
      $this->paymentObject->getRequest()->getAccount()->set('iban', $this->iban);
      $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);

      $this->paymentObject->_dryRun=false;

      $this->paymentObject->authorize();
      

      $this->assertTrue($this->paymentObject->getResponse()->isSuccess(), 'Transaction failed : '.print_r($this->paymentObject->getResponse(), 1));
      $this->assertFalse($this->paymentObject->getResponse()->isError(),
          'authorize failed : '.print_r($this->paymentObject->getResponse()->getError(), 1));
      
      return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
  }

    /**
     * Capture Test
     *
     * @depends Authorize
     * @test
     *
     * @param $referenceId string
     *
     * @return string
     */
    public function Capture($referenceId=null)
    {
        $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
        $this->paymentObject->getRequest()->basketData($timestamp, 13.12, $this->currency, $this->secret);
  
        $this->paymentObject->capture((string)$referenceId);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
        $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
  
        $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
        $this->assertFalse($response[1]->isPending(), 'capture is pending');
        $this->assertFalse($response[1]->isError(), 'capture failed : '.print_r($response[1]->getError(), 1));
  
        return (string)$response[1]->getPaymentReferenceId();
    }
  
  /**
   * Test case for a single direct debit debit
   *
   * @return string payment reference id for the direct debit transaction
   * @group connectionTest
   * @test
   */
  public function Debit()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 13.42, $this->currency, $this->secret);
      $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');
      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
  
      $this->paymentObject->getRequest()->getAccount()->set('iban', $this->iban);
      $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
  
      $this->paymentObject->debit();
          
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
  
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1], 1));
      $this->assertFalse($response[1]->isError(), 'authorize failed : '.print_r($response[1]->getError(), 1));
      
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Test case for direct debit refund
   *
   * @param string $referenceId reference id of the direct debit to refund
   *
   * @return string payment reference id of the direct debit refund transaction
   * @depends Debit
   * @test
   * @group connectionTest
   */
  public function Refund($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 3.54, $this->currency, $this->secret);
  
      $this->paymentObject->refund((string)$referenceId);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
      
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
      $this->assertFalse($response[1]->isPending(), 'authorize on registration is pending');
      $this->assertFalse($response[1]->isError(), 'authorizet on registration failed : '.print_r($response[1]->getError(), 1));
  
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Test case for a single direct debit debit
   *
   * @return string payment reference id for the direct debit transaction
   * @group connectionTest
   * @test
   */
  public function Registration()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 13.42, $this->currency, $this->secret);
      $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');
      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
  
      $this->paymentObject->getRequest()->getAccount()->set('iban', $this->iban);
      $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
  
      $this->paymentObject->registration();
       
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
  
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1], 1));
      $this->assertFalse($response[1]->isError(), 'authorize failed : '.print_r($response[1]->getError(), 1));
  
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Test case for a direct debit reversal of a existing authorisation
   *
   * @param string $referenceId payment reference id of the direct debit authorisation
   *
   * @return string payment reference id for the credit card reversal transaction
   * @depends Authorize
   * @test
   * @group connectionTest
   */
  public function Reversal($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);
  
      $this->paymentObject->reversal((string)$referenceId);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
  
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
      $this->assertFalse($response[1]->isPending(), 'reversal is pending');
      $this->assertFalse($response[1]->isError(), 'reversal failed : '.print_r($response[1]->getError(), 1));
  
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Tast case for a direct debit rebill of an existing debit or capture
   *
   * @param $referenceId string payment reference id of the direct debit debit or capture
   *
   * @return string payment reference id for the direct debit rebill transaction
   * @depends Debit
   * @test
   * @group connectionTest
   */
  public function Rebill($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);
  
      $this->paymentObject->rebill((string)$referenceId);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
  
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
      $this->assertFalse($response[1]->isPending(), 'reversal is pending');
      $this->assertFalse($response[1]->isError(), 'reversal failed : '.print_r($response[1]->getError(), 1));
  
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Test case for direct debit authorisation on a registration
   *
   * @param $referenceId string reference id of the direct debit registration
   *
   * @return string payment reference id of the direct debit authorisation
   * @depends Registration
   * @test
   * @group  connectionTest
   */
  public function AuthorizeOnRegistration($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

      $this->paymentObject->authorizeOnRegistration((string)$referenceId);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
  
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
      $this->assertFalse($response[1]->isPending(), 'authorize on registration is pending');
      $this->assertFalse($response[1]->isError(), 'authorizet on registration failed : '.print_r($response[1]->getError(), 1));
  
      return (string)$response[1]->getPaymentReferenceId();
  }
}
