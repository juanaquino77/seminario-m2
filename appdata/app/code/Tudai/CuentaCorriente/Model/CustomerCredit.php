<?php
/**
 * Class CreditAccount
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Tudai\CuentaCorriente\Model;

class CustomerCredit
    extends \Magento\Payment\Model\Method\AbstractMethod
{

    const PAYMENT_METHOD_USERCREDIT_CODE = 'customer_credit';


    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_USERCREDIT_CODE;


    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;


    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        //Me traigo por injección de dependencia la sesión del usuario
        $this->customerSession = $customerSession;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig,
            $logger, $resource, $resourceCollection, $data);
    }


    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        $isAvailable = parent::isAvailable($quote);

        $limit = $this->_scopeConfig->getValue('payment/customer_credit/limit');
        $total = $quote->getData('base_grand_total');

        //Si ya viene habilitado el método (verificando si está activo)
        if($isAvailable){
          if ($total > $limit) {
            return false;
          }
            //Tomo el valor de si tiene habilitado el credito o no para saber si lo muestro
            $isAvailable = (bool) $this->customerSession->getCustomer()->getData('enable_customer_credit');
        }

        return $isAvailable;
    }
}
