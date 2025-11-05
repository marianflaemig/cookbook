<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This dedicated form class ensures all extensions, including CSRF,
 * are loaded correctly by Symfony's service container.
 */
class DeleteRecipeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // This form is intentionally left blank as it only needs to provide
        // the CSRF token and the HTTP method spoofing, which are
        // handled by the options in configureOptions.
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_token_id' => 'delete_recipe',
        ]);
    }
}
