<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\UserBundle\Form\Type\CustomerRegistrationType as BaseRegistrationType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class CustomerRegistrationType extends BaseRegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('receiveNewsletter', 'choice', [
                'choices' => [true => 'sylius.ui.yes', false => 'sylius.ui.no'],
                'label' => 'app.ui.receive_newsletter',
                'label_attr' => ['class' => 'text-muted pull-left'],
            ])
        ;
    }
}
