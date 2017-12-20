<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This classe provides authentification parameter for the payment api
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class UserParameterGroup extends AbstractParameterGroup
{
    /**
     * UserLogin
     *
     *
     * @var string login (mandatory)
     */
    public $login;

    /**
     * UserPwd
     *
     * The Passwort of the payment account
     *
     * @var string pwd (mandatory)
     */
    public $pwd;

    /**
     * user login getter
     *
     * @return string login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     *  user password getter
     *
     * @return string password
     */
    public function getPassword()
    {
        return $this->pwd;
    }

    /**
     * Setter for the user login parameter
     *
     * This is one of the main authentication parameter
     *
     * @param string $login user login, e.g. 31ha07bc8142c5a171744e5aef11ffd3
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\UserParameterGroup
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Setter for the user password parameter
     *
     * This is one of the main authentication parameter
     *
     * @param string $pwd user password parameter, e.g. DAJapaewa434
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\UserParameterGroup
     */
    public function setPassword($pwd)
    {
        $this->pwd = $pwd;
        return $this;
    }
}
