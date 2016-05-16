<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\PaymentStepType as BasePaymentStepType;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class PaymentStepType extends BasePaymentStepType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentMethod', 'sylius_payment_method_choice', [
                'label' => 'sylius.form.checkout.payment_method',
                'expanded' => true,
                'property_path' => 'lastPayment.method',
                'channel' => $this->channelContext->getChannel(),
                'chosenShippingMethod' => $options['data']->getLastShipment()->getMethod(),
                'constraints' => [
                    new NotBlank(['message' => 'sylius.checkout.payment_method.not_blank']),
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('chosenShippingMethod', ShippingMethodInterface::class);
    }
}
