<?php

namespace App\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['settings'] as $setting) {
            $builder->add($setting['name'], $setting['formFieldType'], [
                'data' => $setting['value'] ?? null,
                'label' => $setting['label'],
                'required' => false,
                'attr' => [
                    'placeholder' => $setting['placeholder'],
                ]
            ]);
        }

        $builder->add('submit', SubmitType::class, [

        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'settings' => [],
        ]);
    }

}