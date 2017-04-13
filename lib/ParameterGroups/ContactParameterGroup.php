<?php

namespace Heidelpay\PhpApi\ParameterGroups;

/**
 * This class provides every api parameter related to the customers contact data
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
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
    public $email = null;

    /**
     * Customer Ip
     *
     * IP number of the customer. Used e.g. for statisticsor risk checks.
     * Data Format has to be either IPv4 (000.000.000.000) or IPv6 (2a02:1205:13eb:fc60:81e4:2e05:ef9e:2776)
     *
     * @var string ip address of the customer (mandatory)
     */
    public $ip = null;

    /**
     * Contact Mobile
     *
     * Used e.g. for risk management.
     * Has to start with a digit or a '+'
     *
     * @var string mobile phone of the customer (optional)
     */
    public $mobile = null;

    /**
     * Contact Phone
     *
     * Used e.g. for risk management.
     * Has to start with a digit or a '+'
     *
     * @var string phone of the customer (optional)
     */
    public $phone = null;

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
}
