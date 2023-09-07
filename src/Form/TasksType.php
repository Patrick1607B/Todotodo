<?php

namespace App\Form;

use App\Entity\Status;
use App\Entity\Tasks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'status'
            ])
            ->add('deadLine')
            // ->add('etat')
            // ->add('tasksTodolists')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
