<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CategoryBannerType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', ['label' => 'app.ui.file'])
            ->add('url', 'url', ['label' => 'app.ui.url', 'required' => false])
            ->add('enabled', 'checkbox', ['label' => 'sylius.ui.enabled'])
            ->add('showOnCategories', 'sylius_taxon_choice', [
                'multiple' => true,
                'by_reference' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => $this->dataClass,
                'validation_groups' => function (FormInterface $form) {
                    $data = $form->getData();
                    $validationGroups = $this->validationGroups;
                    if (null !== $data && null === $data->getId()) {
                        $validationGroups[] = 'category_banner_create';
                    }

                    return $validationGroups;
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_category_banner';
    }
}
