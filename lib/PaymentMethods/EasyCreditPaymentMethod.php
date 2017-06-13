<?php

namespace Heidelpay\PhpApi\PaymentMethods;

use Heidelpay\PhpApi\TransactionTypes\AuthorizeOnRegistrationTransactionType;
use Heidelpay\PhpApi\TransactionTypes\InitializeTransactionType;

/**
 * Easy Credit Payment Method
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link https://dev.heidelpay.de/php-api
 * @author Stephano Vogel
 * @package heidelpay/php-api/paymentmethods/easycredit
 */
class EasyCreditPaymentMethod
{
    use AuthorizeOnRegistrationTransactionType;
    use BasicPaymentMethodTrait;
    use InitializeTransactionType;

    /**
     * Payment code for this payment method
     * @var string payment code
     */
    protected $_paymentCode = 'HP';

    /**
     * Payment brand name for this payment method
     * @var string brand name
     */
    protected $_brand = 'EASYCREDIT';
}
