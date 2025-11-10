<?php
namespace Evoxlab\FixPrefix\Theme\Design\Config;

use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Theme\Ui\Component\Design\Config\DataProvider as ConfigDataProvider;

class DataProvider extends ConfigDataProvider
{
    private ResourceConnection $resource;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = [],
        ?ResourceConnection $resourceConnection = null
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $storeManager,
            $meta,
            $data,
            $resourceConnection
        );
        $this->resource = $resourceConnection ?? \Magento\Framework\App\ObjectManager::getInstance()
            ->get(ResourceConnection::class);
    }

    public function getData()
    {
        $storeManager = (new \ReflectionProperty(ConfigDataProvider::class, 'storeManager'));
        $storeManager->setAccessible(true);
        /** @var StoreManagerInterface $sm */
        $sm = $storeManager->getValue($this);

        if ($sm->isSingleStoreMode()) {
            $websites = $sm->getWebsites();
            $singleStoreWebsite = array_shift($websites);

            $this->addFilter($this->filterBuilder->setField('store_website_id')->setValue($singleStoreWebsite->getId())->create());
            $this->addFilter($this->filterBuilder->setField('store_group_id')->setConditionType('null')->create());
        }

        // ✅ nossa leitura usando ResourceConnection->getTableName()
        $conn  = $this->resource->getConnection();
        $table = $this->resource->getTableName('core_config_data');
        $themeConfigData = $conn->fetchAll(
            $conn->select()->from($table, ['scope','scope_id','path','value'])
                ->where('path = ?', 'design/theme/theme_id')
        );

        $data = parent::getData();
        foreach ($data['items'] as & $item) {
            $item += ['default' => __('Global')];

            $scope  = ($item['store_id']) ? 'stores' : (($item['store_website_id']) ? 'websites' : 'default');
            $scopeId = (int) ($item['store_website_id'] ?? 0);
            $themeId = (int) ($item['theme_theme_id'] ?? 0);

            $criteria = ['scope' => $scope, 'scope_id' => $scopeId, 'value' => $themeId];
            $configData = array_filter($themeConfigData, fn($row) => array_intersect_assoc($criteria, $row) === $criteria);

            $item += ['short_description' => $configData ? '' : __('Using Default Theme')];
        }
        return $data;
    }
}
