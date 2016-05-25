<?php

namespace AppBundle\Form\EventListener;

use AppBundle\Entity\Customer;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class NewsletterChangeSubscriber implements EventSubscriberInterface
{
    /**
     * @var GeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @param GeneratorInterface $tokenGenerator
     */
    public function __construct(GeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $customerData = $event->getData();
        $customer = $event->getForm()->getData();

        if ($customerData['receiveNewsletter'] !== $customer->isReceiveNewsletter()) {
            $customerData = $this->handleCustomerSubscription($customerData);
            $event->setData($customerData);
        }
    }

    /**
     * @param array $customerData
     *
     * @return array
     */
    private function handleCustomerSubscription(array $customerData)
    {
        if ($customerData['receiveNewsletter']) {
            $token = $this->tokenGenerator->generate(40);
            $customerData['unsubscribeToken'] = $token;

            return $customerData;
        }

        $customerData['unsubscribeToken'] = null;

        return $customerData;
    }
}
