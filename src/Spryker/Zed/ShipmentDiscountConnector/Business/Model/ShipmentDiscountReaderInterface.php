<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;

interface ShipmentDiscountReaderInterface
{
    /**
     * @return array<string>
     */
    public function getCarrierList();

    /**
     * @return array<string>
     */
    public function getMethodList();
}
