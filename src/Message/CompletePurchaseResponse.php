<?php

namespace Omnipay\Idram\Message;

use Illuminate\Support\Arr;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Class CompletePurchaseResponse
 * @package Omnipay\Idram\Message
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Indicates whether transaction was successful
     * @return bool
     */
    public function isSuccessful()
    {
        return !empty($this->data['success']);
    }

    /**
     * Get transaction ID, generated by merchant
     * @return mixed|string
     */
    public function getTransactionId()
    {
        return Arr::get($this->data, 'EDP_BILL_NO');
    }

    /**
     * Get transaction reference, generated by gateway
     * @return mixed|null|string
     */
    public function getTransactionReference()
    {
        return Arr::get($this->data, 'EDP_TRANS_ID');
    }
}