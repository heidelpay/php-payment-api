<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
/**
 * This class provides every api parmater related to the customers contact data  
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
class ContactParameterGroup extends AbstractParameterGroup {
    
    /**
     * @var string email address of the customer (mandatory)
     */
    public $email = NULL;
    
    /**
     * @var string ip address of the customer (mandatory)
     */
    public $ip = NULL;
        
    /**
     * ContactEmail getter
     * @return string email
     */
    
    public function getEmail(){
        return $this->email;
    }
    
    /**
     * ContactIp getter
     * @return string ip
     */
    
    public function getIp(){
        return $this->ip;
    }
    
}