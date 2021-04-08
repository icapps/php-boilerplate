<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordResetType extends AbstractType
{

    public function __construct(private TranslatorInterface $translator)
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => $this->translator->trans('Password'),
                'attr' => [
                    'class' => 'form-text c-input__field',// TODO: change styling
                ],
            ]);
    }
}
