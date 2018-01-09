<?php

namespace Heidelpay\PhpPaymentApi\Constants;

/**
 * Class for Payment Transaction Type constants
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2017-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel <development@heidelpay.de>
 *
 * @package heidelpay\php-payment-api\constants
 *
 * @since 1.3.0 First time this was introduced.
 */
class TransactionType
{
    const CREDIT = 'CD';
    const DEBIT = 'DB';
    const RESERVATION = 'PA';
    const CAPTURE = 'CP';
    const REVERSAL = 'RV';
    const REFUND = 'RF';
    const REBILL = 'RB';
    const CHARGEBACK = 'CB';
    const RECEIPT = 'RC';
    const INITIALIZE = 'IN';

    const REGISTRATION = 'RG';
    const REREGISTRATION = 'RR';
    const DEREGISTRATION = 'DR';

    const SCHEDULE = 'SD';
    const RESCHEDULE = 'RS';
    const CHANGE_SCHEDULE = self::RESCHEDULE;
    const DESCHEDULE = 'DS';
    const END_SCHEDULE = self::DESCHEDULE;

    const CHARGEBACK_NOTIFICATION = 'CN';
    const CHARGEBACK_REVERSAL = 'CR';
    const RECONCILIATION = 'RL';
    const FINALIZE = 'FI';
    const PAYMENT_NOTIFICATION = 'PN';
}
