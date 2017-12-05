<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * Summary
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay
 * @subpackage php-api
 * @category php-api
 */
class Frontend extends AbstractPushMapper
{
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

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Frontend->$field)) {
            return (string)$xmlElement->Transaction->Frontend->$field;
        }

        return null;
    }
}
