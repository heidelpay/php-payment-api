<?php
namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod;

/**
 *
 *  Credit card test
 *
 *  Connection tests can fail due to network issues and scheduled downtimes.
 *  This does not have to mean that your integration is broken. Please verify the given debug information
 *
 *  Warning:
 *  - Use of the following code is only allowed with this sandbox credit card information.
 *
 *  - Using this code or even parts of it with real credit card information  is a violation
 *  of the payment card industry standard aka pci3.
 *
 *  - You are not allowed to save, store and/or process credit card information any time with your systems.
 *    Always use Heidelpay payment frame solution for a pci3 conform credit card integration.
 *
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
class CreditCardPaymentMerhodTest extends TestCase
{
    /** authentification parameter for heidelpay api
     *
     * @var array authentification parameter for heidelpay api
     */
    protected $authentification = array(
        '31HA07BC8142C5A171745D00AD63D182', //SecuritySender
        '31ha07bc8142c5a171744e5aef11ffd3', //UserLogin
        '93167DE7', //UserPassword
        '31HA07BC8142C5A171744F3D6D155865', //TransactionChannel
        true //Sandbox mode
    );

    /** customer address
     *
     * @var array customer address
     */
    protected $customerDetails = array(
        'Heidel', //NameGiven
        'Berger-Payment', //NameFamily
        null, //NameCompany
        '1234', //IdentificationShopperId
        'Vagerowstr. 18', //AddressStreet
        'DE-BW', //AddressState
        '69115', //AddressZip
        'Heidelberg', //AddressCity
        'DE', //AddressCountry
        'development@heidelpay.de' //Costumer
    );

    /**
     *  Account holder
     *
     * @var string Account holder
     */
    protected $holder =   'Heidel Berger-Payment';
    
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
     * Credit card number
     * Do not use real credit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string credit card number
     */
    protected $creditCartNumber       =   '4711100000000000';
    /**
     * Credit card brand
     * Do not use real credit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string credit card brand
     */
    protected $creditCardBrand        =   'VISA';
    /**
     * Credit card verification
     * Do not use real credit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string credit card verification
     */
    protected $creditCardVerification = '123';
    /**
     * Credit card expiry month
     *
     * @var string credit card verification
     */
    protected $creditCardExpiryMonth = '04';
    /**
     * Credit card expiry year
     *
     * @var string credit card year
     */
    protected $creditCardExpiryYear  = '2040';
    
    /**
     * PaymentObject
     *
     * @var \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod
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
   * Set up function will create a credit card object for each testcase
   *
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp()
  {
      $CreditCard = new CreditCardPaymentMethod();
    
      $CreditCard->getRequest()->authentification(...$this->authentification);
    
      $CreditCard->getRequest()->customerAddress(...$this->customerDetails);
    
      $CreditCard->_dryRun=true;
    
      $this->paymentObject = $CreditCard;
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
   * Test case for credit cart registration whitout payment frame
   *
   * @return string payment reference id to the credit card registration
   * @group  connectionTest
   * @test
   */
  public function Registration()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
       
      $this->paymentObject->registration('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');
      

      /* disable frontend (iframe) and submit the credit card information directly (only for testing) */
      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
      $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
      $this->paymentObject->getRequest()->getAccount()->set('number', $this->creditCartNumber);
      $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
      $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
      $this->paymentObject->getRequest()->getAccount()->set('brand', $this->creditCardBrand);
      $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);
      
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
      
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
      $this->assertFalse($response[1]->isPending(), 'registration is pending');
      $this->assertFalse($response[1]->isError(), 'registration failed : '.print_r($response[1]->getError(), 1));
      
      return (string)$response[1]->getPaymentReferenceId();
  }

  /**
   * Test case for a credit card debit on a registration
   *
   * @param $referenceId string reference id of the credit card registration
   *
   * @return string payment reference id to the credit card debit transaction
   * @depends Registration
   * @group  connectionTest
   * @test
   */
  public function DebitOnRegistration($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

      $this->paymentObject->_dryRun=false;

      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

      $this->paymentObject->debitOnRegistration((string)$referenceId);

      
      $this->assertTrue($this->paymentObject->getResponse()->isSuccess(), 'Transaction failed : '.print_r($this->paymentObject->getResponse()->getError(), 1));
      $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'debit on registration is pending');
      $this->assertFalse($this->paymentObject->getResponse()->isError(), 'debit on registration failed : '.print_r($this->paymentObject->getResponse()->getError(), 1));
      
      return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
  }

  /**
   * Test case for credit card authorisation on a registration
   *
   * @param referenceId string reference id of the credit card registration
   * @param mixed $referenceId
   *
   * @return string payment reference id of the credit card authorisation
   * @depends Registration
   * @group  connectionTest
   * @test
   */
  public function AuthorizeOnRegistration($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

      $this->paymentObject->_dryRun=false;

      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

      $this->paymentObject->authorizeOnRegistration((string)$referenceId);

      $this->assertTrue($this->paymentObject->getResponse()->isSuccess(), 'Transaction failed : '.print_r($this->paymentObject->getResponse()->getError(), 1));
      $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'authorize on registration is pending');
      $this->assertFalse($this->paymentObject->getResponse()->isError(), 'authorizet on registration failed : '.print_r($this->paymentObject->getResponse()->getError(), 1));
      
      return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
  }
  
  /**
   * @depends AuthorizeOnRegistration
   * @test
   *
   * @param mixed $referenceId
   */
  public function Capture($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
      
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
   * Test case for credit card refund
   *
   * @param $referenceId string reference id of the credit card debit/capture to refund
   *
   * @return string payment reference id of the credit card refund transaction
   * @depends Capture
   * @group connectionTest
   * @test
   */
  public function Refund($referenceId=null)
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
      
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
   * Tast case for a single credit card debit transaction whithout payment frame
   *
   * @return string payment reference id for the credit card debit transaction
   * @group connectionTest
   * @test
   */
  public function Debit()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
      
      $this->paymentObject->debit('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');
      
      /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
      $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
      $this->paymentObject->getRequest()->getAccount()->set('number', $this->creditCartNumber);
      $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
      $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
      $this->paymentObject->getRequest()->getAccount()->set('brand', $this->creditCardBrand);
      $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
      
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
      $this->assertFalse($response[1]->isPending(), 'debit is pending');
      $this->assertFalse($response[1]->isError(), 'debit failed : '.print_r($response[1]->getError(), 1));
      
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Tast case for a single credit card authorisation whithout payment frame
   *
   * @return string payment reference id for the credit card authorize transaction
   * @group connectionTest
   * @test
   */
  public function Authorize()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
      
      $this->paymentObject->authorize('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');
      
      /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
      $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
      $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
      $this->paymentObject->getRequest()->getAccount()->set('number', $this->creditCartNumber);
      $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
      $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
      $this->paymentObject->getRequest()->getAccount()->set('brand', $this->creditCardBrand);
      $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->convertToArray();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
      
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(), 1));
      $this->assertFalse($response[1]->isPending(), 'authorize is pending');
      $this->assertFalse($response[1]->isError(), 'authorize failed : '.print_r($response[1]->getError(), 1));
      
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Test case for a credit card reversal of a existing authorisation
   *
   * @var string payment reference id of the credit card authorisation
   *
   * @return string payment reference id for the credit card reversal transaction
   * @depends Authorize
   * @group connectionTest
   * @test
   *
   * @param mixed $referenceId
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
   * Tast case for a credit card rebill of an existing debit or capture
   *
   * @var string payment reference id of the credit card debit or capture
   *
   * @return string payment reference id for the credit card rebill transaction
   * @depends Debit
   * @group connectionTest
   * @test
   *
   * @param mixed $referenceId
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
}
