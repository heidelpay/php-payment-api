<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
/**
 * This classe provides authentification parameter for the payment api
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

class UserParameterGroup extends AbstractParameterGroup {
    
    /**
     * UserLogin
     * 
     * 
     * @var string login (mandatory) 
     */
    public $login = NULL;
    
    /**
     * UserPwd
     * 
     * The Passwort of the payment account
     * @var string pwd (mandatory)
     */
    public $pwd = NULL;
    
    /**
     * user login getter
     * @return string login 
     */
    
    public function getLogin(){
        return $this->login;
    }
    
    /**
     *  user password getter
     * @return string  password
     */
    
    public function getPassword(){
        return $this->pwd;
    }
}