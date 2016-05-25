<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\EventListener\NewsletterChangeSubscriber;
use Sylius\Bundle\UserBundle\Form\Type\CustomerRegistrationType as BaseCustomerRegistrationType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CustomerRegistrationType extends BaseCustomerRegistrationType
{
    /**
     * @var GeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @param string $dataClass
     * @param array $validationGroups
     * @param RepositoryInterface $customerRepository
     * @param GeneratorInterface $tokenGenerator
     */
    public function __construct(
        $dataClass,
        array $validationGroups,
        RepositoryInterface $customerRepository,
        GeneratorInterface $tokenGenerator
    ) {
        parent::__construct($dataClass, $validationGroups, $customerRepository);

        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('receiveNewsletter', 'choice', [
                'choices' => [true => 'sylius.ui.yes', false => 'sylius.ui.no'],
                'data' => true,
                'label' => 'app.ui.receive_newsletter',
                'label_attr' => ['class' => 'text-muted pull-left'],
            ])
            ->add('unsubscribeToken', 'hidden')
            ->addEventSubscriber(new NewsletterChangeSubscriber($this->tokenGenerator))
        ;

        $builder->get('firstName')->setRequired(false);
        $builder->get('lastName')->setRequired(false);
        $builder->get('phoneNumber')->setRequired(false);
    }
}
