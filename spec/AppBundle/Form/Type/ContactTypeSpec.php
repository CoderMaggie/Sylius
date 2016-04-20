<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AppBundle\Form\Type;

use AppBundle\Form\Type\ContactType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @mixin ContactType
 *
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ContactTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Form\Type\ContactType');
    }

    function it_extends_abstract_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_has_a_valid_name()
    {
        $this->getName()->shouldReturn('contact');
    }

    function it_builds_the_form_with_proper_fields(FormBuilderInterface $builder)
    {
        $builder
            ->add('firstName', 'text', Argument::any())
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('lastName', 'text', Argument::any())
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('email', 'text', Argument::any())
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('subject', 'text', Argument::any())
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('message', 'textarea', Argument::any())
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $this->buildForm($builder, []);
    }

    function it_configures_options(OptionsResolver $resolver)
    {
        $resolver->setDefaults(Argument::withKey('constraints'))->shouldBeCalled();

        $this->configureOptions($resolver);
    }
}
