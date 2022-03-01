<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Razoyo\AnimalProfile\Controller\Profile;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Razoyo\AnimalProfile\Animal;
use Razoyo\AnimalProfile\Model\Attribute\Source\AnimalProfile as AnimalProfileAttributeSource;

class Photo implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Constructor
     *
     * @param PageFactory $resultPageFactory
     * @param Json $json
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Json $json,
        LoggerInterface $logger,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->serializer = $json;
        $this->logger = $logger;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $animalParam = $this->request->getParam('animal');

        switch ($animalParam) {
            case AnimalProfileAttributeSource::ANIMAL_DOG:
                $photo = new Animal\Dog();
                break;
            
            case AnimalProfileAttributeSource::ANIMAL_LLAMA:
                $photo = new Animal\Llama();
                break;
            
            case AnimalProfileAttributeSource::ANIMAL_ANTEATER:
                $photo = new Animal\Anteater();
                break;
            
            default:
                $photo = new Animal\Cat();
        }

        try {
            return $this->jsonResponse(['photo' => $photo->getContent()]);
        } catch (LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @return ResultInterface
     */
    public function jsonResponse($responseJson = '')
    {
        //$this->http->getHeaders()->clearHeaders();
        $this->response->setHeader('Content-Type', 'application/json');
        return $this->response->setBody(
            $this->serializer->serialize($responseJson)
        );
    }
}

