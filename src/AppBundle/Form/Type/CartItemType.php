<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\CartItemType as BaseCartItemType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CartItemType extends BaseCartItemType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $choices = range(0, 20);

        /**
         * Since we cannot have the quantity equal to 0, we are unsetting that value.
         * By that we get an array with values equal to keys: [1 => 1, 2 => 2, 3 => 3, ...]
         */
        unset($choices[0]);

        $builder->add('quantity', ChoiceType::class, [
            'choices' => $choices,
        ]);
    }
}
