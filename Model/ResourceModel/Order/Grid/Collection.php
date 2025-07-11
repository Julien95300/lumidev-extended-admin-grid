<?php
namespace LumiDev\ExtendedAdminGrid\Model\ResourceModel\Order\Grid;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Collection extends \Magento\Sales\Model\ResourceModel\Order\Grid\Collection
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
       $mainTable = 'sales_order_grid',
       $resourceModel = \Magento\Sales\Model\ResourceModel\Order::class
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
            $this->getSelect()->joinLeft(
                ["so" => "sales_order"],
                "main_table.entity_id = so.entity_id",
                ['customer_taxvat']
            )->distinct();
        }

        parent::_renderFiltersBefore();
    }

    protected function _initSelect()
    {
        $this->addFilterToMap('created_at', 'main_table.created_at');
        $this->addFilterToMap('base_grand_total', 'main_table.base_grand_total');
        $this->addFilterToMap('grand_total', 'main_table.grand_total');
        $this->addFilterToMap('store_id', 'main_table.store_id');
        $this->addFilterToMap('order_id', 'main_table.order_id');
        $this->addFilterToMap('order_increment_id', 'main_table.order_increment_id');
        $this->addFilterToMap('billing_name', 'main_table.billing_name');
        $this->addFilterToMap('shipping_name', 'main_table.shipping_name');
        $this->addFilterToMap('status', 'main_table.status');

        parent::_initSelect();
    }
}
