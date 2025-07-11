<?php
namespace LumiDev\ExtendedAdminGrid\Model\ResourceModel\Order\Invoice\Orders\Grid;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Collection extends \Magento\Sales\Model\ResourceModel\Order\Invoice\Orders\Grid\Collection
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Collection constructor.
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */

    public function __construct(
       \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
       \Psr\Log\LoggerInterface $logger,
       \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
       \Magento\Framework\Event\ManagerInterface $eventManager,
       \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
       $mainTable = 'sales_invoice_grid',
       $resourceModel = \Magento\Sales\Model\ResourceModel\Order\Invoice::class
   ) {
       $this->scopeConfig = $scopeConfig;
       parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
   }


    protected function _renderFiltersBefore()
    {
        $enabled = $this->scopeConfig->isSetFlag(
            'extentedadmingrid/general/enabled',
            ScopeInterface::SCOPE_STORE
        );

        if ($enabled) {
            // Jointure sales_order_address pour récupérer le pays de livraison
            $this->getSelect()->joinLeft(
                ["soa" => "sales_order_address"],
                "main_table.order_id = soa.parent_id AND soa.address_type = 'shipping'",
                ['country_id']
            )->distinct();

            // Jointure sales_order pour récupérer le numéro de TVA client
            $this->getSelect()->joinLeft(
                ["so" => "sales_order"],
                "main_table.order_id = so.entity_id",
                ['customer_taxvat']
            )->distinct();

            // Jointure sales_invoice pour récupérer discount_amount et base_tax_amount
            $this->getSelect()->joinLeft(
                ["si" => "sales_invoice"],
                "main_table.entity_id = si.entity_id",
                ['discount_amount', 'base_tax_amount']
            )->distinct();

        }

        parent::_renderFiltersBefore();
    }

    protected function _initSelect()
    {
        $this->addFilterToMap('base_grand_total', 'main_table.base_grand_total');
        $this->addFilterToMap('grand_total', 'main_table.grand_total');
        $this->addFilterToMap('store_id', 'main_table.store_id');
        $this->addFilterToMap('store_name', 'main_table.store_name');
        $this->addFilterToMap('order_id', 'main_table.order_id');
        $this->addFilterToMap('order_increment_id', 'main_table.order_increment_id');
        $this->addFilterToMap('shipping_name', 'main_table.shipping_name');
        $this->addFilterToMap('status', 'main_table.status');
        $this->addFilterToMap('entity_id', 'main_table.entity_id');

        // Colonnes jointes (si activé)
        $this->addFilterToMap('country_id', 'soa.country_id');
        $this->addFilterToMap('customer_taxvat', 'so.customer_taxvat');
        $this->addFilterToMap('discount_amount', 'si.discount_amount');
        $this->addFilterToMap('base_tax_amount', 'si.base_tax_amount');

        parent::_initSelect();
    }
}
