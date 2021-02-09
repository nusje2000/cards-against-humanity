<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Form\Type;

use Nusje2000\CAH\Domain\Player\Username;
use Nusje2000\CAH\Infrastructure\Form\Model\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class RegistrationType extends AbstractType
{
    /**
     * @param array<mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('username', TextType::class, [
            'constraints' => Username::getConstraints(),
        ]);

        $builder->add('password', PasswordType::class, [
            'constraints' => new NotBlank(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Registration::class);
    }
}
