<?php

namespace AppBundle\Entity;

use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder
{
    /**
     * @return int
     */
    public function getSubtotal()
    {
        $subtotal = 0;

        foreach ($this->items as $item) {
            $subtotal += $item->getSubtotal();
        }

        return $subtotal;
    }
}
