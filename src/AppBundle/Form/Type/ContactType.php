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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', ['label' => 'sylius.form.contact_request.first_name',])
            ->add('lastName', 'text', ['label' => 'sylius.form.contact_request.last_name',])
            ->add('email', 'text', ['label' => 'sylius.form.contact_request.email',])
            ->add('subject', 'text', ['label' => 'sylius.ui.subject',])
            ->add('message', 'textarea', ['label' => 'sylius.form.contact_request.message',]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $constraints = new Collection([
                'firstName' => [new NotBlank(['message' => 'app.contact.first_name.not_blank'])],
                'lastName' => [new NotBlank(['message' => 'app.contact.last_name.not_blank'])],
                'email' => [
                    new NotBlank(['message' => 'app.contact.email.not_blank']),
                    new Email(['message' => 'app.contact.email.valid']),
                ],
                'subject' => [new NotBlank(['message' => 'app.contact.subject.not_blank'])],
                'message' => [new NotBlank(['message' => 'app.contact.message.not_blank'])],
        ]);

        $resolver->setDefaults(['constraints' => $constraints]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'contact';
    }
}
