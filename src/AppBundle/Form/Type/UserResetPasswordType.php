<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\UserBundle\Form\Model\PasswordReset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class UserResetPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', 'repeated', [
                'type' => 'password',
                'first_options' => ['label' => 'sylius.form.user.password.label'],
                'second_options' => ['label' => 'sylius.form.user.password.confirmation'],
                'invalid_message' => 'sylius.user.plainPassword.mismatch',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PasswordReset::class,
            'validation_groups' => ['app_user_update'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_user_reset_password';
    }
}
