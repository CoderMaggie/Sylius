<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\PaymentBundle\Form\Type\PaymentMethodType as BasePaymentMethodType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class PaymentMethodType extends BasePaymentMethodType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('availableForShippingMethods', 'sylius_shipping_method_choice', [
            'required' => false,
            'multiple' => true,
            'label' => 'app.ui.available_for_shipping_methods',
        ]);

        $builder->add('paymentInstructions', 'textarea', [
            'required' => false,
            'label' => 'app.ui.payment_instructions',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('availableForShippingMethods', []);
    }
}
