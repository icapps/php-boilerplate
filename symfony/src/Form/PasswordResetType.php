<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordResetType extends AbstractType
{

    public function __construct(
        private TranslatorInterface $translator
    ) {
        //
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => $this->translator->trans('icapps.website.lbl_user.reset.password'),
                'attr' => [
                    'class' => 'input is-fullwidth is-expanded',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('icapps.website.lbl_user.reset.form_submit', [], 'messages'),
                'attr' => ['class' => 'button is-link is-light is-fullwidth'],
                'disabled' => true,
            ]);
    }
}
