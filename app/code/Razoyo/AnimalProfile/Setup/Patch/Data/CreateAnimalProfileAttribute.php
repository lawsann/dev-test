<?php
    
namespace Razoyo\AnimalProfile\Setup\Patch\Data;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Razoyo\AnimalProfile\Model\Attribute\Source\AnimalProfile;

class CreateAnimalProfileAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup,
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Creates the animal attribute for customer entity
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributeCode = 'animal_profile';

        $customerSetup->addAttribute(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            $attributeCode,
            [
                'type'          => 'int',
                'label'         => 'Animal Profile',
                'input'         => 'select',
                'source'        => AnimalProfile::class,
                'default'       => AnimalProfile::ANIMAL_CAT,
                'required'      => true,
                'user_defined'  => true,
                'sort_order'    => 40000,
                'visible'       => true,
                'system'        => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
            ]
        );

        $customerSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            $attributeCode
        );

        $customerSetup->getEavConfig()
            ->getAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, $attributeCode)
            ->setData('used_in_forms', ['adminhtml_customer', 'customer_account_edit'])
            ->save();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Does not clear customer attribute values, to keep client
     * preference for future occasions
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Lists the patch dependencies
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Lists the patch aliases
     */
    public function getAliases()
    {
        return [];
    }
}
