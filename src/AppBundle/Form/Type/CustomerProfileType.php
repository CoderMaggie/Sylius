<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\UserBundle\Form\Type\CustomerProfileType as BaseCustomerProfileType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CustomerProfileType extends BaseCustomerProfileType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('gender')
            ->add('secondPhoneNumber', 'text', [
                'required' => false,
                'label' => 'app.ui.phone_number_evening',
            ])
        ;
    }
}
