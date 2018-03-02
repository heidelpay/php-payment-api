<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Frontend Parameter Group
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\push-mapping
 */
class Frontend extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'CssPath' => 'css_path',
        'Enabled' => 'enabled',
        'Language' => 'language',
        'Mode' => 'mode',
        'PaymentFrameUrl' => 'payment_frame_url',
        'PreventAsyncRedirect' => 'prevent_async_redirect',
        'RedirectUrl' => 'redirect_url',
        'ResponseUrl' => 'response_url',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Frontend->$field)) {
            return (string)$xmlElement->Transaction->Frontend->$field;
        }

        return null;
    }
}
