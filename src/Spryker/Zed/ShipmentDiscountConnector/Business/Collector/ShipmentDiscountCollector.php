<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollector as ShipmentDiscountWithoutMultiShipmentCollector;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceInterface;

class ShipmentDiscountCollector extends ShipmentDiscountWithoutMultiShipmentCollector
{
    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface|\Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface $carrierDiscountDecisionRule
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentDiscountDecisionRuleInterface $carrierDiscountDecisionRule,
        ShipmentDiscountConnectorToShipmentServiceInterface $shipmentService
    ) {
        parent::__construct($carrierDiscountDecisionRule, $shipmentService);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];

        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            if ($shipmentTransfer === null) {
                continue;
            }

            $expenseTransfer = $this->findQuoteExpenseByShipment($quoteTransfer, $shipmentTransfer);
            if ($expenseTransfer === null) {
                continue;
            }

            if ($this->shipmentDiscountDecisionRule->isExpenseSatisfiedBy($quoteTransfer, $expenseTransfer, $clauseTransfer)) {
                $discountableItems[] = $this->createDiscountableItemTransfer($expenseTransfer, $quoteTransfer->getPriceMode());
            }
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findQuoteExpenseByShipment(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): ?ExpenseTransfer
    {
        $itemShipmentKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if (!$expenseTransfer->getShipment()) {
                continue;
            }

            $expenseShipmentKey = $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment());
            if (
                $expenseShipmentKey === $itemShipmentKey
                && $expenseTransfer->getType() === ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE
            ) {
                return $expenseTransfer;
            }
        }

        return null;
    }
}
