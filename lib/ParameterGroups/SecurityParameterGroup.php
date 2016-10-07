<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;

/**
 * This class provides authentification parameters
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

class SecurityParameterGroup extends AbstractParameterGroup {
    
    /**
     * SecuritySender
     * 
     * This parameter is the main authentification parameter
     * @var string sender (mandatory) 
     */
    public $sender = NULL;
    
    /**
     * SecuritySender getter
     * @return string sender
     */
    
    public function getSender(){
        return $this->sender;
    }
}