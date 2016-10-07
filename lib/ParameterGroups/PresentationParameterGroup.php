<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
/**
 * This class provides the api parameter for amount and currency.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */

class PresentationParameterGroup extends AbstractParameterGroup {
    
    /**
     * PresentationAmount
     * 
     * Order or transaction amount.
     * @var float amount (mandatory) 
     */
    public $amount = NULL;
    
    /**
     * PresentationCurrency
     * 
     * Currency of the transaction. ISO 4217
     * @var string currency code ISO 4217 (mandatory)
     */
    public $currency = NULL;
    
    /**
     * PresentationAmount getter
     * @return string amount
     */
    
    public function getAmount(){
        return $this->amount;
    }
    
    /**
     * PresentationCurrency getter
     * @return string currency
     */
    
    public function getCurrency(){
        return $this->currency;
    }
}