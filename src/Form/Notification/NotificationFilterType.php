<?php

namespace App\Form\Notification;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationFilterType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page', HiddenType::class)
            ->add('id', NumberType::class, [
                'required' => false,
            ])
            ->add('dateFrom', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('dateTo', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => array_flip($options['statuses'] ?? [])
            ])
            ->add('submit', SubmitType::class, [
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => null,
            'statuses' => null,
        ]);
    }
}