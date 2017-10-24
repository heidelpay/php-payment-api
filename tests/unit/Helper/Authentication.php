<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 24.10.2017
 * Time: 12:23
 */
namespace Heidelpay\Tests\PhpApi\unit\Helper;

class Authentication
{
    /** @var string $securitySender */
    protected $securitySender = '31HA07BC8142C5A171745D00AD63D182';

    /** @var string $userLogin */
    protected $userLogin = '31ha07bc8142c5a171744e5aef11ffd3';

    /** @var string $userPassword */
    protected $userPassword = '93167DE7';

    /** @var string $transactionChannel */
    protected $transactionChannel = '';

    /**
     * authentication parameters for heidelpay api
     *
     * @param bool $sandboxMode
     *
     * @return array
     */
    public function getAuthenticationArray($sandboxMode = true)
    {
        return [
            $this->securitySender,
            $this->userLogin,
            $this->userPassword,
            $this->transactionChannel,
            $sandboxMode
        ];
    }

    //<editor-fold desc="Getters/Setters">

    /**
     * @return string
     */
    public function getSecuritySender()
    {
        return $this->securitySender;
    }

    /**
     * @param string $securitySender
     */
    public function setSecuritySender($securitySender)
    {
        $this->securitySender = $securitySender;
    }

    /**
     * @return string
     */
    public function getUserLogin()
    {
        return $this->userLogin;
    }

    /**
     * @param string $userLogin
     */
    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;
    }

    /**
     * @return string
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * @param string $userPassword
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
    }

    /**
     * @return string
     */
    public function getTransactionChannel()
    {
        return $this->transactionChannel;
    }

    /**
     * @param string $transactionChannel
     */
    public function setTransactionChannel($transactionChannel)
    {
        $this->transactionChannel = $transactionChannel;
    }

    //</editor-fold>
}
