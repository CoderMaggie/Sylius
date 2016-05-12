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
            ->remove('birthday')
            ->add('birthday', 'birthday', [
                'label' => 'sylius.form.customer.birthday',
                'label_attr' => ['class' => 'text-muted pull-left'],
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'DD/MM/YYYY'],
                'required' => false,
            ])
            ->add('secondPhoneNumber', 'text', [
                'required' => false,
                'label' => 'app.ui.phone_number_evening',
                'label_attr' => ['class' => 'text-muted pull-left'],
                'attr' => ['placeholder' => '(123) 456-7890'],
            ])
            ->add('receiveNewsletter', 'choice', [
                'choices' => [true => 'sylius.ui.yes', false => 'sylius.ui.no'],
                'empty_data' => true,
                'required' => false,
                'label' => 'app.ui.receive_newsletter',
                'label_attr' => ['class' => 'text-muted pull-left'],
            ])
            ->add('nickname', 'text', [
                'required' => false,
                'label' => 'app.ui.nickname',
                'label_attr' => ['class' => 'text-muted pull-left'],
            ])
            ->add('favouriteGames', 'textarea', [
                'required' => false,
                'label' => 'app.ui.favourite_games',
                'label_attr' => ['class' => 'text-muted pull-left'],
            ])
            ->add('futurePurchases', 'textarea', [
                'required' => false,
                'label' => 'app.ui.future_games_purchases',
                'label_attr' => ['class' => 'text-muted pull-left'],
            ])
        ;
    }
}
