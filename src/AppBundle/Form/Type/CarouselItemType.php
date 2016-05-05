<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CarouselItemType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', ['label' => 'app.ui.file'])
            ->add('position', 'integer', ['label' => 'app.ui.position', 'required' => false])
            ->add('tooltip', 'text', ['label' => 'app.ui.tooltip', 'required' => false])
            ->add('url', 'url', ['label' => 'app.ui.url', 'required' => false])
            ->add('enabled', 'checkbox', ['label' => 'sylius.ui.enabled'])
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
                        $validationGroups[] = 'carousel_item_create';
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
        return 'app_carousel_item';
    }
}
