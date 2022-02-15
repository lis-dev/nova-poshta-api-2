<?php

namespace LisDev;

use LisDev\Exceptions\ApplicationException;
use LisDev\Factories\RequestFactory;
use LisDev\Factories\ResponseHandlerFactory;
use LisDev\Models\Address;
use LisDev\Models\Common;
use LisDev\Models\ContactPerson;
use LisDev\Models\Counterparty;
use LisDev\Models\CustomModel;
use LisDev\Models\InternetDocument;
use LisDev\Request\RequestInterface;
use LisDev\Request\ResponseHandler\ResponseHandlerInterface;

class NovaPoshta
{
    /** @var RequestInterface API Requests */
    protected $request;
    /** @var ResponseHandlerInterface Formats response and handle API error */
    protected $responseHandler;

    /**
     * Nova Poshta API Facade
     * @param string $token
     * @param Config|null $config
     * @throws ApplicationException
     */
    public function __construct($token, Config $config = null)
    {
        $config = $config === null ? new Config() : $config;

        $this->request = $this->createRequest($token, $config);
        $this->responseHandler = $this->createResponseHandler($config);
    }

    /**
     * @return RequestInterface
     * @throws ApplicationException
     */
    private function createRequest($token, Config $config)
    {
        return RequestFactory::create(
            $token,
            $config->getConnectionType(),
            $config->getLanguage(),
            $config->getTimeout(),
            $config->getFormat()
        );
    }

    /**
     * @return ResponseHandlerInterface
     * @throws ApplicationException
     */
    private function createResponseHandler(Config $config)
    {
        return ResponseHandlerFactory::create($config->getFormat(), $config->getThrowError());
    }

    /**
     * Works with custom model
     * @return CustomModel
     */
    public function model($model)
    {
        return new CustomModel($this->request, $this->responseHandler, $model);
    }

    /**
     * Working with express waybills
     * @return InternetDocument
     */
    public function InternetDocument()
    {
        return new InternetDocument($this->request, $this->responseHandler);
    }

    /**
     * Working with Counterparty data
     * @return Counterparty
     */
    public function Counterparty()
    {
        return new Counterparty($this->request, $this->responseHandler);
    }

    /**
     * Working with Counterparty Contact Person data
     * @return ContactPerson
     */
    public function ContactPerson()
    {
        return new ContactPerson($this->request, $this->responseHandler);
    }

    /**
     * Working with addresses
     * @return Address
     */
    public function Address()
    {
        return new Address($this->request, $this->responseHandler);
    }

    /**
     * Working with handbooks
     * @return Common
     */
    public function Common()
    {
        return new Common($this->request, $this->responseHandler);
    }
}