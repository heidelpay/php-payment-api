<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the customers contact data
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 */
class ContactParameterGroup extends AbstractParameterGroup
{
    /**
     * Contact EMail
     *
     * Used e.g. transmission of direct debit mandates.
     *
     * @var string email address of the customer (mandatory)
     */
    public $email;

    /**
     * Customer Ip
     *
     * IP number of the customer. Used e.g. for statisticsor risk checks.
     * Data Format has to be either IPv4 (000.000.000.000) or IPv6 (2a02:1205:13eb:fc60:81e4:2e05:ef9e:2776)
     *
     * @var string ip address of the customer (mandatory)
     */
    public $ip;

    /**
     * Contact Mobile
     *
     * Used e.g. for risk management.
     * Has to start with a digit or a '+'
     *
     * @var string mobile phone of the customer (optional)
     */
    public $mobile;

    /**
     * Contact Phone
     *
     * Used e.g. for risk management.
     * Has to start with a digit or a '+'
     *
     * @var string phone of the customer (optional)
     */
    public $phone;

    /**
     * ContactEmail getter
     *
     * @return string email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * ContactIp getter
     *
     * @return string ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * ContactMobile getter
     *
     * @return string mobile
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * ContactPhone getter
     *
     * @return string phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Setter for the customer email account
     *
     * @param $email string customer email
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Setter for the customers ip address
     *
     * @param string $ip customer ip address
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Setter for the customers mobile phone number
     *
     * @param string $mobile mobile phone number
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * Setter for customer phone number
     *
     * @param string $phone phone number
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
}
