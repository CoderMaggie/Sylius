<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\UserBundle\Form\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class UserChangePasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', 'password', [
                'label' => 'sylius.form.user.password.current',
            ])
            ->add('newPassword', 'repeated', [
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
            'data_class' => ChangePassword::class,
            'validation_groups' => ['app_user_update'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_user_change_password';
    }
}
