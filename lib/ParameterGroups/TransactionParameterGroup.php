<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

use Heidelpay\PhpPaymentApi\Constants\TransactionMode;

/**
 * Transaction parameter group
 *
 * You can change the transaction channel and mode by using this class
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class TransactionParameterGroup extends AbstractParameterGroup
{
    /**
     * TransactionChannel
     *
     * You can use this parameter to set the channel used for this transaction.
     * Depending on the used payment method, you have to use an other channel.
     *
     * @var string channel (mandatory)
     */
    public $channel;

    /**
     * TransactionMode
     *
     * Mode tells the payment system weather to make a real transaction or just perform
     * a connector test. Please have a look into Heidelpay documentation for more
     * details.
     *
     * @var string mode (mandatory)
     */
    public $mode = TransactionMode::CONNECTOR_TEST;

    /**
     * @var string
     */
    public $response;

    /**
     * TransactionChannel getter
     *
     * @return string channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * TransactionMode getter
     *
     * @return string mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Setter for the transaction channel
     *
     * A channel can be used as a separater between to two sales channels like german and uk market.
     * Another reason for a different channel is that some payment methods share one channel.
     *
     * @param string $channel transaction channel id, e.g. 31HA07BC8142C5A171749A60D979B6E4
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * Setter for the transaction mode
     *
     * @param string $mode, e.g. LIVE
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Setter for the response
     *
     * @param string $response e.g. 'SYNC'
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }
}
