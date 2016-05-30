<?php

namespace AppBundle\Controller\Frontend;

use Sylius\Bundle\WebBundle\Controller\Frontend\Account\OrderController as BaseOrderController;
use Sylius\Bundle\PayumBundle\Request\GetStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class OrderController extends BaseOrderController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function afterPurchaseAction(Request $request)
    {
        $token = $this->getHttpRequestVerifier()->verify($request);
        $this->getHttpRequestVerifier()->invalidate($token);

        $status = new GetStatus($token);
        $this->getPayum()->getGateway($token->getGatewayName())->execute($status);
        $payment = $status->getFirstModel();
        $order = $payment->getOrder();
        $this->checkAccessToOrder($order);

        $orderStateResolver = $this->get('sylius.order_processing.state_resolver');
        $orderStateResolver->resolvePaymentState($order);
        $orderStateResolver->resolveShippingState($order);

        $this->getOrderManager()->flush();

        return $this->redirectToRoute('sylius_checkout_thank_you');
    }
}
