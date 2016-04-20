<?php

namespace AppBundle\Form\Type\Grid\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Jędrzejewski <pawel@app.org>
 */
class TaxonFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setOptional([
                'field'
            ])
            ->setAllowedTypes([
                'field' => ['string']
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'sylius_taxon_choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_grid_filter_taxon';
    }
}
