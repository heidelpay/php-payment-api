<?php

namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
/**
 * This class provides a key value store for api parmater 
 * 
 * All parameter that start with Criterion will be given to the payment api and
 * send back in return. This class also provides some special parameter like
 * §secret and $sdk_name for instance.
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

class CriterionParameterGroup extends AbstractParameterGroup {
    
    /**
     * Currently used payment methode
     * @var sting payment methode
     */
    public $payment_method = NULL;
    
    /**
     * hash to verify the response
     * @var string hash to verify the response
     */
    public $secret = NULL;
    
    /**
     * Sdk name
     * @var string sdk name
     */
    public $sdk_name = "Heidelpay\PhpApi";
    
    /**
     * Sdk version
     * @var string version
     */
    public $sdk_version = "16.12.19";
    
    /**
     * CriterionPaymentMethod getter
     * @return string payment method
     */
    
    public function getPaymentMethod(){
        return $this->payment_method;
    }
    
    /**
     * CriterionSecret setter
     * @var string identificaton transaction id
     * @var string secret of your application
     * @return object instance of the CriterionParameterGroup
     */
    
    public function setSecret($value, $secret){
        $this->secret = hash('sha512',$value.$secret);
        return $this; 
    }
    
    /**
     * CriterionSecret getter
     * @return string secret
     */
    
    public function getSecretHash(){
        return $this->secret;
    }
    
    
    /**
     * Magic setter without property exception
     * 
     *  This class has his own setter, because criterions can be used as key value store.
     *  You can use any key and value which is a valid post parameter.
     *
     * @param string $key
     * @param string $value
     * @return \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup
     */
    
    public function set($key, $value){
        $key = strtolower($key);
            $this->$key = $value;
            return $this;
    }
    
}