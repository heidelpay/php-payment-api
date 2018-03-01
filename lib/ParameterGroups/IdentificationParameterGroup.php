<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter used to identify a transaction.
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
class IdentificationParameterGroup extends AbstractParameterGroup
{
    /**
     * Creditor id
     *
     * @var string creditor id
     */
    public $creditor_id;

    /**
     * IdentificationShopperId
     *
     * Identification number of your customer, should be given by your application.
     * You can use this for example in the HIP backend for search operations or for reporting as well.
     *
     * @var string customer identification number (optional)
     */
    public $shopperid;

    /**
     * IdentificationShortId
     *
     * This is a human readable version of the IdentificationUniqeId. It
     * can be used for example if you have to ask for a transaction via phone.
     *
     * @var string heidelpay short identifier
     */
    public $shortid;

    /**
     * IdentificatonTransactionId
     *
     * This is an identifier given by your application. It can be a basket
     * or order number.
     *
     * @var string order identification number (optional)
     */
    public $transactionid;

    /**
     * IdentificationrefernceId
     *
     * In some cases like refund or capture, you have to tell the payment api that
     * this transaction is related to an other. In this case, set
     * IdentificationReferenceId to the IndetificationUniqeId of the related
     * transaction.
     *
     * @var string payment reference Id, for example the uniqe Id of the invoice autorisation
     */
    public $referenceid;

    /**
     * IdentificationUniqeId
     *
     * This is the transaction identifier given by the payment api. This id can
     * be used for related transactions like refund or capture.
     *
     * @var string payment long identifier also know as uniqeId
     */
    public $uniqueid;

    /**
     * IdentificationCreditorId getter
     *
     * @return string creditor id
     */
    public function getCreditorId()
    {
        return $this->creditor_id;
    }

    /**
     * IdentificationShopperid getter
     *
     * @return string shopperid
     */
    public function getShopperId()
    {
        return $this->shopperid;
    }

    /**
     * IdentificationShortid getter
     *
     * @return string shortid
     */
    public function getShortId()
    {
        return $this->shortid;
    }

    /**
     * IdentificationTransactionId getter
     *
     * @return string transaction id
     */
    public function getTransactionId()
    {
        return $this->transactionid;
    }

    /**
     * IdentificationReferenceId getter
     *
     * @return string reference id
     */
    public function getReferenceId()
    {
        return $this->referenceid;
    }

    /**
     * IdentificationUniqueId getter
     *
     * @return string unique id
     */
    public function getUniqueId()
    {
        return $this->uniqueid;
    }

    /**
     * Setter for the customer id of your application
     *
     * @param string $shopperid customer id, e.g. 12042
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup
     */
    public function setShopperid($shopperid)
    {
        $this->shopperid = $shopperid;
        return $this;
    }

    /**
     * Setter for the transaction id
     *
     * The transaction id is an identifier given by your application to allow matching between
     * your system and the payment system. This can be, e.g. an order id or invoice id.
     *
     * @param string $transactionid, e.g. order-1109
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup
     */
    public function setTransactionid($transactionid)
    {
        $this->transactionid = $transactionid;
        return $this;
    }

    /**
     * Setter for the payment reference id or unique id
     *
     * Some kinds of transactions needs to reference to another transaction. This can be done
     * by setting this parameter with the unique id of the reference transaction., e.g. if you
     * use debitOnRegistration you have to set the id of the registration.
     *
     * @param string $referenceid, e.g. 31HA07BC8142C5A171745D00AD63D182
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup
     */
    public function setReferenceid($referenceid)
    {
        $this->referenceid = $referenceid;
        return $this;
    }
}
