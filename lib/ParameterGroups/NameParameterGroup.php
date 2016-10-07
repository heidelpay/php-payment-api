<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;

/**
 * This class provides every api parameter of the "name" namespace  
 * 
 * It will be used for parameters like given name, but also for salutation etc.
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



class NameParameterGroup extends AbstractParameterGroup {
    
    /**
     * NameCompany
     * 
     * Used to set the company name of your customer. 
     * @var string company name (optional) 
     */
    public $company = NULL;
    
    /**
     * NameGiven
     * 
     * First name of your customer. 
     * @var string given name of the customer (mandatory)
     */
    public $given = NULL;
    
    /**
     * NameFamily
     * 
     * Family name of your customer
     * @var string family name of the customer (mandatory)
     */
    public $family = NULL;
    
    /**
     * NameCompany getter
     * @return string company
     */
    
    public function getCompany(){
        return $this->company;
    }
    
    /**
     * NameGiven getter
     * @return string given
     */
    
    public function getGiven(){
        return $this->given;
    }
    
    /**
     * NameFamily getter
     * @return string family
     */
    
    public function getFamily(){
        return $this->family;
    }
}