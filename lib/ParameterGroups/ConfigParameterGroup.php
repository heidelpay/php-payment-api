<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
/**
 * This class provides every api parmaters for the form configuration  
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
class ConfigParameterGroup extends AbstractParameterGroup {
    
    /**
     * Supported bank countries for this payment method
     * @var string bankcountry
     */
    public $bankcountry = NULL;
    
    /**
     *  Supported brands countries for this payment method
     * @var string brands
     */
    public $brands = NULL;
        
    /**
     * Config bankcountry getter
     * @return string email
     */
    
    public function getBankCountry(){
        return json_decode($this->bankcountry,true);
    }
    
    /**
     * Config brands getter
     * @return string brands 
     */
    
    public function getBrands(){
        return json_decode($this->brands,true);
    }
    
}