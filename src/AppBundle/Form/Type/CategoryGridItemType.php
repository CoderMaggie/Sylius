<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryGridItemType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', 'integer', ['label' => 'app.ui.position', 'required' => false])
            ->add('category', 'sylius_taxon_choice', ['label' => 'app.ui.category'])
            ->add('enabled', 'checkbox', ['label' => 'sylius.ui.enabled'])
            ->add('file', 'file', ['label' => 'app.ui.file', 'required' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_category_grid_item';
    }
}
