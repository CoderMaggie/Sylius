<?php

namespace AppBundle\Controller\Frontend;

use AppBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class SubscriptionController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function subscribeGuestAction(Request $request)
    {
        $form = $this->createForm($this->get('sylius.form.type.customer_guest'), null, ['csrf_token_id' => 'subscribe_form']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $this->subscribe($customer);
        }
        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function subscribeLoggedAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $this->get('sylius.context.customer')->getCustomer();
        if (null === $customer) {
            throw new AccessDeniedException();
        }
        if ($customer->isReceiveNewsletter()) {
            $this->addFlash('notice', 'app.ui.newsletter_subscribe_notice');

            return $this->redirect($request->headers->get('referer'));
        }

        $this->subscribe($customer);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param string $unsubscribeToken
     *
     * @return Response
     */
    public function unsubscribeAction($unsubscribeToken)
    {
        $customerRepository = $this->get('sylius.repository.customer');

        /** @var Customer $customer */
        $customer = $customerRepository->findOneBy(['unsubscribeToken' => $unsubscribeToken]);
        if (null === $customer) {
            $this->addFlash('error', 'app.ui.newsletter_unsubscribe_error');

            return $this->redirectToRoute('sylius_homepage');
        }

        $this->unsubscribe($customer);

        return $this->redirectToRoute('sylius_homepage');
    }

    /**
     * @param Customer $customer
     */
    private function subscribe(Customer $customer)
    {
        $unsubscribeToken = $this->get('sylius.user.token_generator')->generate(40);
        $customer->setUnsubscribeToken($unsubscribeToken);
        $customer->setReceiveNewsletter(true);

        $this->saveCustomer($customer);

        $this->get('sylius.email_sender')->send(
            'app_newsletter_subscription',
            [$customer->getEmail()],
            ['customer' => $customer, 'subject' => 'app.email.newsletter_subscription.subject']
        );

        $this->addFlash('success', 'app.ui.newsletter_subscribe_success');
    }

    /**
     * @param Customer $customer
     */
    private function unsubscribe(Customer $customer)
    {
        $customer->setReceiveNewsletter(false);
        $customer->setUnsubscribeToken(null);

        $this->saveCustomer($customer);

        $this->addFlash('success', 'app.ui.newsletter_unsubscribe_success');
    }

    /**
     * @param Customer $customer
     */
    private function saveCustomer(Customer $customer)
    {
        $entityManager = $this->get('sylius.manager.customer');
        $entityManager->persist($customer);
        $entityManager->flush();
    }
}
