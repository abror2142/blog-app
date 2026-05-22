<?php

namespace WebDev\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WebDev\BlogBundle\DTO\BlogFilter;
use WebDev\BlogBundle\Enum\Status;

class BlogFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Search by title',
                'required' => false,
            ])
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'label' => 'Status',
                'required' => false,
                'placeholder' => 'All statuses',
                'choice_label' => fn (Status $status) => $status->label(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogFilter::class,
            'csrf_protection' => false,
        ]);
    }
}
