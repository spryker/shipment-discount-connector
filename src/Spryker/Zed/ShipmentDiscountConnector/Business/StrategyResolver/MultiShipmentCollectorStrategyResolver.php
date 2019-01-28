<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver;

use Closure;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface;

/**
 * @deprecated Will be removed in next major release.
 */
class MultiShipmentCollectorStrategyResolver implements MultiShipmentCollectorStrategyResolverInterface
{
    /**
     * @var array|\Closure[]
     */
    protected $strategyContainer;

    /**
     * @param \Closure[] $strategyContainer
     */
    public function __construct(array $strategyContainer)
    {
        $this->strategyContainer = $strategyContainer;
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface
     */
    public function resolveByType(string $type): ShipmentDiscountCollectorInterface
    {
        if (!defined('\Generated\Shared\Transfer\ItemTransfer::SHIPMENT')) {
            $this->assertRequiredStrategyWithoutMultiShipmentContainerItems($type);

            return call_user_func($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        $this->assertRequiredStrategyWithMultiShipmentContainerItems($type);

        return call_user_func($this->strategyContainer[$type][static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithoutMultiShipmentContainerItems(string $type): void
    {
        if (!isset($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[$type][static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithMultiShipmentContainerItems(string $type): void
    {
        if (!isset($this->strategyContainer[$type][static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[$type][static::STRATEGY_KEY_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }
}