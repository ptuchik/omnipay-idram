<?php

namespace Omnipay\Idram\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Class PurchaseRequest
 * @package Omnipay\Idram\Message
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * Sets the request language.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * Get the request language.
     * @return $this
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Sets the request account ID.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    /**
     * Get the request account ID.
     * @return $this
     */
    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    /**
     * Sets the request secret key.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    /**
     * Get the request secret key.
     * @return $this
     */
    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    /**
     * Sets the request email.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the request email.
     * @return $this
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set custom data to get back as is
     *
     * @param array $value
     *
     * @return $this
     */
    public function setCustomData(array $value)
    {
        return $this->setParameter('customData', $value);
    }

    /**
     * Get custom data
     * @return mixed
     */
    public function getCustomData()
    {
        return $this->getParameter('customData', []) ?? [];
    }

    /**
     * Prepare data to send
     * @return array
     */
    public function getData()
    {
        $this->validate('language', 'amount', 'accountId', 'secretKey', 'email');

        return array_merge($this->getCustomData(), [
            'EDP_LANGUAGE'    => strtoupper($this->getLanguage()),
            'EDP_REC_ACCOUNT' => $this->getAccountId(),
            'EDP_DESCRIPTION' => $this->getDescription(),
            'EDP_AMOUNT'      => $this->getAmount(),
            'EDP_BILL_NO'     => $this->getTransactionId(),
            'EDP_EMAIL'       => $this->getEmail(),
        ]);
    }

    /**
     * Send data and return response instance
     *
     * @param mixed $data
     *
     * @return \Omnipay\Common\Message\ResponseInterface|\Omnipay\Idram\Message\PurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}