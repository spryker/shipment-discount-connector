<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentDiscountConnector\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig;
use Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DecisionRule\ShipmentCarrierDecisionRulePlugin;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DecisionRule\ShipmentMethodDecisionRulePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDiscountConnector
 * @group Business
 * @group Facade
 * @group ShipmentDiscountConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentDiscountConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsCarrierSatisfiedBy(): void
    {
        $shipmentCarrierTransfer = (new ShipmentCarrierTransfer())
            ->setIdShipmentCarrier(1);

        $shipmentTransfer = (new ShipmentTransfer())
            ->setCarrier($shipmentCarrierTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->fromArray([
            'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
        ]);

        $itemTransfer = new ItemTransfer();
        $quoteTransfer = (new QuoteTransfer())
            ->setShipment($shipmentTransfer)
            ->setItems(new ArrayObject([$itemTransfer]))
            ->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentCarrierDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '=',
            'value' => $shipmentTransfer->getCarrier()->getIdShipmentCarrier(),
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->isCarrierSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsMethodSatisfiedBy(): void
    {
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(1);

        $shipmentTransfer = (new ShipmentTransfer())
            ->setMethod($shipmentMethodTransfer);

        $expenseTransfer = (new ExpenseTransfer())
            ->fromArray([
                'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
            ]);

        $itemTransfer = new ItemTransfer();
        $quoteTransfer = (new QuoteTransfer())
            ->setShipment($shipmentTransfer)
            ->setItems(new ArrayObject([$itemTransfer]))
            ->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentMethodDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '=',
            'value' => $shipmentMethodTransfer->getIdShipmentMethod(),
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->isMethodSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsPriceSatisfiedBy(): void
    {
        $itemTransfer = new ItemTransfer();
        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->fromArray([
            'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
            'unitGrossPrice' => 2500,
            'taxRate' => 19,
            'quantity' => 1,
        ]);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setItems(new ArrayObject([$itemTransfer]));
        $quoteTransfer->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentCarrierDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '>=',
            'value' => 20,
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->isPriceSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCollectDiscountByShipmentCarrier(): void
    {
        $shipmentCarrierTransfer = (new ShipmentCarrierTransfer())
            ->setIdShipmentCarrier(1);

        $shipmentTransfer = (new ShipmentTransfer())
            ->setCarrier($shipmentCarrierTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->fromArray([
            'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->setShipment($shipmentTransfer)
            ->setItems(new ArrayObject([new ItemTransfer()]))
            ->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentCarrierDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '=',
            'value' => $shipmentTransfer->getCarrier()->getIdShipmentCarrier(),
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->collectDiscountByShipmentCarrier($quoteTransfer, $clauseTransfer);

        $this->assertCount(1, $result);
    }

    /**
     * @return void
     */
    public function testCollectDiscountByShipmentMethod(): void
    {
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(1);

        $shipmentTransfer = (new ShipmentTransfer())
            ->setMethod($shipmentMethodTransfer);

        $expenseTransfer = (new ExpenseTransfer())
            ->fromArray([
                'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
            ]);

        $quoteTransfer = (new QuoteTransfer())
            ->setShipment($shipmentTransfer)
            ->setItems(new ArrayObject([new ItemTransfer()]))
            ->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentMethodDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '=',
            'value' => $shipmentMethodTransfer->getIdShipmentMethod(),
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->collectDiscountByShipmentMethod($quoteTransfer, $clauseTransfer);
        $this->assertCount(1, $result);
    }

    /**
     * @return void
     */
    public function testCollectDiscountByShipmentPrice(): void
    {
        $itemTransfer = new ItemTransfer();
        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->fromArray([
            'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
            'unitGrossPrice' => 2500,
        ]);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setItems(new ArrayObject([$itemTransfer]));
        $quoteTransfer->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentCarrierDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '>=',
            'value' => 20,
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->collectDiscountByShipmentPrice($quoteTransfer, $clauseTransfer);
        $this->assertCount(1, $result);
    }

    /**
     * @return void
     */
    public function testCollectDiscountByShipmentCarrierNegative(): void
    {
        $shipmentCarrierTransfer = (new ShipmentCarrierTransfer())
            ->setIdShipmentCarrier(1);

        $shipmentTransfer = (new ShipmentTransfer())
            ->setCarrier($shipmentCarrierTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->fromArray([
            'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->setShipment($shipmentTransfer)
            ->setItems(new ArrayObject([new ItemTransfer()]))
            ->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentCarrierDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '=',
            'value' => 2,
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->collectDiscountByShipmentCarrier($quoteTransfer, $clauseTransfer);

        $this->assertCount(0, $result);
    }

    /**
     * @return void
     */
    public function testCollectDiscountByShipmentMethodNegative(): void
    {
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(1);

        $shipmentTransfer = (new ShipmentTransfer())
            ->setMethod($shipmentMethodTransfer);

        $expenseTransfer = (new ExpenseTransfer())
            ->fromArray([
                'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
            ]);

        $quoteTransfer = (new QuoteTransfer())
            ->setShipment($shipmentTransfer)
            ->setItems(new ArrayObject([new ItemTransfer()]))
            ->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentMethodDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '=',
            'value' => 2,
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->collectDiscountByShipmentMethod($quoteTransfer, $clauseTransfer);
        $this->assertCount(0, $result);
    }

    /**
     * @return void
     */
    public function testCollectDiscountByShipmentPriceNegative(): void
    {
        $itemTransfer = new ItemTransfer();
        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->fromArray([
            'type' => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
            'unitGrossPrice' => 2500,
        ]);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setItems(new ArrayObject([$itemTransfer]));
        $quoteTransfer->setExpenses(new ArrayObject([$expenseTransfer]));

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->fromArray([
            'field' => ShipmentCarrierDecisionRulePlugin::DECISION_RULE_FIELD_NAME,
            'operator' => '>=',
            'value' => 40,
            'acceptedTypes' => [
                'number',
            ],
        ], true);

        $result = $this->createFacade()->collectDiscountByShipmentPrice($quoteTransfer, $clauseTransfer);
        $this->assertCount(0, $result);
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface
     */
    protected function createFacade(): ShipmentDiscountConnectorFacadeInterface
    {
        return $this->tester->getLocator()->shipmentDiscountConnector()->facade();
    }
}
