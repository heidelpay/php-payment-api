<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to customer data
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class CustomerParameterGroup extends AbstractParameterGroup
{
    /** @var string $optIn_2 Represents the customers agreement to the gtc (agb). */
    public $optIn_2 =  'FALSE';

    //<editor-fold desc="Getter/Setter">

    /**
     * @return bool
     */
    public function isOptIn2()
    {
        return $this->optIn_2 === 'TRUE';
    }

    /**
     * @param bool $optIn_2
     *
     * @return CustomerParameterGroup
     */
    public function setOptIn2($optIn_2)
    {
        $this->optIn_2 = $optIn_2 ? 'TRUE' : 'FALSE';
        return $this;
    }

    //</editor-fold>
}
