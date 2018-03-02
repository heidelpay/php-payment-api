<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides authentification parameters
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
class SecurityParameterGroup extends AbstractParameterGroup
{
    /**
     * @var string sender (mandatory) This parameter is the main authentification parameter
     */
    public $sender;

    /**
     * SecuritySender getter
     *
     * @return string sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Setter for the security sender parameter
     *
     * This is one of the main authentication parameter
     *
     * @param string $sender authentication parameter, e.g. 31HA07BC8142C5A171745D00AD63D182
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\SecurityParameterGroup
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }
}
