<?php
namespace Tudai\CuentaCorriente\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Costumer\Model\Costumer
use Magento\Costumer\Model\Costumer
use Magento\Eav\Model\Entity\Attribute\Set as AttribruteSet
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttribruteSetFactory
class InstallData implements InstallDataInterface
{
    protected $costumerSetupFactory;
    protected $attributeSetFactory;

    public function __construct(
      CostumerSetupFactory $costumerSetupFactory,
      AttributeSetFactory $attributeSetFactory,
      )
      {
        $this->$costumerSetupFactory = $costumerSetupFactory;
        $this->$attributeSetFactory = $attributeSetFactory;
      }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
      * @var CustomerSetup $customerSetup
      */
       $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
       $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
       $attributeSetId = $customerEntity->getDefaultAttributeSetId();
       /**
        * @var $attributeSet AttributeSet
        */
       $attributeSet = $this->attributeSetFactory->create();
       $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

       $customerSetup->addAttribute(Customer::ENTITY, 'enable_customer_credit', [
           // características del atributo
           'type'         => 'int',
               'label'        => 'Enable Customer Credit',
               'input'        => 'boolean',
               'required'     => false,
               'visible'      => true,
               'user_defined' => true,
               'position'     => 210,
       ]);
       $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'enable_customer_credit')
           ->addData([
               'attribute_set_id'   => $attributeSetId,
               'attribute_group_id' => $attributeGroupId,
               'used_in_forms'      => ['adminhtml_customer'],//you can use other forms also ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
           ]);
       $attribute->save();
    }
}
