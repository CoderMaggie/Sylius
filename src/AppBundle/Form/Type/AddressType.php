<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\AddressingBundle\Form\Type\AddressType as BaseAddressType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class AddressType extends BaseAddressType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('countryCode', 'sylius_country_code_choice', [
                'label' => 'sylius.form.address.country',
                'enabled' => true,
                'placeholder' => false,
                'data' => 'GB'
            ])
        ;
    }
}
