<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Form\InvoiceLineType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'name',
                'label' => 'Client',
                'placeholder' => 'Choisir un client',
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('lines', CollectionType::class, [
                'entry_type' => InvoiceLineType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Lignes de facture',
                'prototype' => true,
                'entry_options' => ['label' => false],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
