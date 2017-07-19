<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DecisionRule;


use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface getFacade()
 */
class ShipmentMethodDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    /**
     * Specification:
     *
     * - Make decision on given Quote or Item transfer.
     * - Use \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface to compare item value with ClauseTransfer.
     * - Returns false when not matching.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFacade()
            ->isMethodSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * Name of field as used in query string
     *
     * @api
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'shipment-method';
    }

    /**
     * @return array
     */
    public function acceptedDataTypes()
    {
        return [
            ComparatorOperators::TYPE_STRING,
        ];
    }

    /**
     * @return array
     */
    public function getQueryStringValueOptions()
    {
        return $this->getFacade()->getMethodList();
    }

}