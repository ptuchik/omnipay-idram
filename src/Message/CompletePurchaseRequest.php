<?php

namespace Omnipay\Idram\Message;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class CompletePurchaseRequest
 * @package Omnipay\Idram\Message
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    /**
     * Prepare and get data
     * @return mixed|void
     */
    public function getData()
    {
        return $this->validateRequest($this->httpRequest->request);
    }

    /**
     * Send data and return response
     *
     * @param mixed $data
     *
     * @return \Omnipay\Common\Message\ResponseInterface|\Omnipay\Idram\Message\CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    /**
     * Validate precheck request and interrupt process with just 'OK' if it is passed
     *
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestData
     */
    protected function validatePrecheckRequest(ParameterBag $requestData)
    {
        if ($requestData->has('EDP_BILL_NO') &&
            $requestData->has('EDP_AMOUNT') &&
            $requestData->get('EDP_PRECHECK') == 'YES' &&
            $requestData->get('EDP_REC_ACCOUNT') == $this->getAccountId) {
            die('OK');
        }
    }

    /**
     * Validate request and return data, merchant has to echo with just 'OK' at the end
     *
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestData
     *
     * @return array
     */
    protected function validateRequest(ParameterBag $requestData)
    {
        // Check if request is precheck or final
        $this->validatePrecheckRequest($requestData);

        $data = $requestData->all();
        $data['success'] = false;

        // Check for required request data
        if ($requestData->has('EDP_PAYER_ACCOUNT') &&
            $requestData->has('EDP_BILL_NO') &&
            $requestData->has('EDP_REC_ACCOUNT') &&
            $requestData->has('EDP_AMOUNT') &&
            $requestData->has('EDP_TRANS_ID') &&
            $requestData->has('EDP_CHECKSUM')) {

            // Generate string to hash for verification
            $txtToHash = $this->getAccountId().':'.
                $requestData->get('EDP_AMOUNT').':'.
                $this->getSecretKey().':'.
                $requestData->get('EDP_BILL_NO').':'.
                $requestData->get('EDP_PAYER_ACCOUNT').':'.
                $requestData->get('EDP_TRANS_ID').':'.
                $requestData->get('EDP_TRANS_DATE');

            // Check hash against checksum and set success status
            $data['success'] = strtoupper($requestData->get('EDP_CHECKSUM')) == strtoupper(md5($txtToHash));
        }

        return $data;
    }
}