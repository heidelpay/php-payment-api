<?php

namespace Heidelpay\PhpPaymentApi\Constants;

/**
 * Class for Brand constants
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
class Brand
{
    // Credit Card
    const AMERICAN_EXPRESS = 'AMEX';
    const DINERS = 'DINERS';
    const DISCOVER = 'DISCOVER';
    const JAPAN_CREDIT_BUREAU = 'JCB';
    const MASTERCARD = 'MASTER';
    const VISA = 'VISA';

    // Debit Card
    const FOURB = 'FOURB';  // 4B
    const CARTE_BANCAIRE = 'CARTEBANCAIRE';
    const CARTE_BLEUE = 'CARTEBLEUE';
    const CARTA_SI = 'CARTASI';
    const DANKORT = 'DANKORT';
    const DELTA = 'DELTA';
    const EURO6000 = 'EURO6000';
    const HSBC = 'HSBC';
    const MAESTRO = 'MAESTRO';
    const MR_CASH = 'MRCASH';
    const NBAD = 'NBAD';
    const NORDEA_DEBIT = 'NORDEADEBIT';
    const POSTEPAY = 'POSTEPAY';
    const SERVIRED = 'SERVIRED';
    const SOLO = 'SOLO';
    const VISA_DEBIT = 'VISADEBIT';
    const VISA_ELECTRON = 'VISAELECTRON';
    const V_PAY = 'VPAY';

    // Invoice
    const BILLSAFE = 'BILLSAFE';
    const PAYOLUTION_DIRECT = 'PAYOLUTION_DIRECT';
    const SANTANDER = 'SANTANDER';

    // Online Transfer
    const EPS = 'EPS';
    const GIROPAY = 'GIROPAY';
    const IDEAL = 'IDEAL';
    const POSTFINANCE_CARD = 'PFCARD';
    const POSTFINANCE_EFINANCE = 'PFEFINANCE';
    const PRZELEWY24 = 'PRZELEWY24';
    const SOFORT = 'SOFORT';
    const SOFORT_PAYCODE = 'SOFORT_PAYCODE';

    // Virtual Account
    const PAYPAL = 'PAYPAL';

    // Payment Card
    const MANGIRKART = 'MANGIRKART';

    // Mobile Payment
    const MOBILE_BUSINESS_ENGINE = 'MBE4_ONE_PHASE';
    const MOBILE_BUSINESS_ENGINE_ABO = 'MBE4_ABO_ONE_PHASE';

    // Wallet
    const MASTERPASS = 'MASTERPASS';

    // Hire Purchase
    const EASYCREDIT = 'EASYCREDIT';
}
