<?php
/**
 * @Copyright © EBoost. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author    eboost10@gmail.com
 * @project   Stock Alert
 */

declare(strict_types=1);

namespace EBoost\StockAlert\Model\ResourceModel\Stock\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity;

/**
 * Class Collection
 */
class Collection extends SearchResult
{
    /**
     * @inheritdoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->joinProductEntityTable();
        return $this;
    }

    /**
     * @return $this
     */
    private function joinProductEntityTable()
    {
        $connection = $this->getConnection();
        $productLinkIdField = $this->getLinkField('catalog_product_entity');
        $productIdField = $connection->quoteIdentifier('main_table.product_id');
        $customerIdField = $connection->quoteIdentifier('main_table.customer_id');

        $productNameId = $this->getProductNameAttributeId();

        $this->getSelect()
             ->joinLeft(
                 ['cpe' => $this->getTable('catalog_product_entity')],
                 "cpe.entity_id={$productIdField}",
                 ['sku']
             )->joinLeft(
                ['cpev' => $this->getTable('catalog_product_entity_varchar')],
                "cpev.attribute_id={$productNameId} AND cpev.store_id = 0 AND cpev.{$productLinkIdField} = cpe.{$productLinkIdField}",
                ['product_name' => 'value']
            )->joinLeft(
                ['ce' => $this->getTable('customer_entity')],
                "ce.entity_id={$customerIdField}",
                ['customer_name' => new \Zend_Db_Expr('CONCAT(ce.firstname," ",ce.lastname)')]
            );

        $this->addFilterToMap('product_name', 'cpev.value');

        return $this;
    }

    /**
     * @param string $tableName
     *
     * @return mixed|string|null
     */
    private function getLinkField(string $tableName)
    {
        $indexList = $this->getConnection()->getIndexList($this->getTable($tableName));
        $pkName = $this->getConnection()->getPrimaryKeyName($this->getTable($tableName));
        $linkIdField = $indexList[$pkName]['COLUMNS_LIST'][0] ?? null;
        if (!$linkIdField) {
            $linkIdField = Entity::DEFAULT_ENTITY_ID_FIELD;
        }
        return $linkIdField;
    }

    /**
     * @param $field
     * @param $condition
     *
     * @return $this|Collection
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'customer_name') {
            $customerTable = $this->getConnection()->getTableName('customer_entity');
            $this->getSelect()->joinLeft(
                ['cust' => $customerTable],
                'main_table.customer_id = cust.entity_id',
                []
            );
            $conditionSql = $this->_getConditionSql(
                'CONCAT(`cust`.`firstname`, " ", `cust`.`lastname`)',
                $condition
            );
            $this->getSelect()->where($conditionSql);
            return $this;
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * @return string
     */
    private function getProductNameAttributeId(): string
    {
        $connection = $this->getConnection();
        $attributeCode = 'name';
        $idSelect = clone $this->getSelect();
        $idSelect->reset();
        $idSelect->from( ['ea' => $this->getTable('eav_attribute')], 'attribute_id')
                 ->joinInner(
                     ['eet' => $this->getTable('eav_entity_type')],
                     $connection->quoteIdentifier('eet.entity_type_id')."=".$connection->quoteIdentifier('ea.entity_type_id'),
                     []
                 )
                 ->where($connection->quoteIdentifier('eet.entity_type_code').' = ?',Product::ENTITY)
                 ->where($connection->quoteIdentifier('ea.attribute_code').' = ?', $attributeCode);
       return $connection->fetchOne($idSelect);
    }
}
