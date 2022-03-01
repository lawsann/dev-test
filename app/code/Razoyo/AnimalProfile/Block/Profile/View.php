<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Razoyo\AnimalProfile\Block\Profile;

use Razoyo\AnimalProfile\Model\Attribute\Source\AnimalProfile as AttributeSource;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ScustomerSession
     */
    private $customerSession;

    /**
     * @var AttributeSource
     */
    private $attributeSource;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param AttributeSource $attributeSource
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        AttributeSource $attributeSource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->attributeSource = $attributeSource;
    }

    /**
     * Retrieves the greeting message
     */
    public function getGreeting()
    {
        return 'Hello ' . $this->customerSession->getCustomer()->getFirstname() . '!';
    }

    /**
     * Retrieves the base URL for the animal photograpy
     */
    public function getPhotoUrl()
    {
        return $this->getUrl('animalid/profile/photo');
    }

    /**
     * Retrieves the save form URL
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('animalid/profile/save');
    }

    /**
     * Retrieves the animal options
     * @return array
     */
    public function getAnimalOptionsJson()
    {
        return json_encode($this->attributeSource->getAllOptions());
    }

    /**
     * Retrieves the animal current animal in customer profile
     * @return array
     */
    public function getCurrentAnimal()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return '';
        }

        return $this->customerSession->getCustomer()->getAnimalProfile() ?? AttributeSource::ANIMAL_CAT;
    }
}
