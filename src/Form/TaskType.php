<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\PhoneNumber;
use App\Entity\Task;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description',TextareaType::class, array(
                'required' => false
            ))
            ->add('levelOfExpertise', ChoiceType::class, array(
                'choices'  => array(
                    'site.novice' => 'Početnik',
                    'site.experienced' => 'Iskusan',
                    'site.expert' => 'Stručnjak',
                ),
            ))
            ->add('budget')
            ->add('duration',IntegerType::class, array(
                'required' => false
            ))
            ->add('dueDate', DateTimeType::class, array(
                'years' => range(date('Y'), date('Y')+2)
            ))
            //->add('approved')
            //->add('publishedAt')
            ->add('user')
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
            ))
            ->add('phoneNumber', EntityType::class, array(
                'class' => PhoneNumber::class,
                'query_builder' => function (EntityRepository $er) use($options){
                    return $er->createQueryBuilder('p')
                        ->where('p.isHidden != true')
                        ->andWhere('p.user = :user')
                        ->setParameter('user', $options['attr']['user'])
                        ->orderBy('p.number', 'ASC');
                },
                'choice_label' => 'number',
            ))
            //->add('location')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
