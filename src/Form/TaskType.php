<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,ButtonType,ChoiceType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType};
use Doctrine\ORM\EntityRepository;

class TaskType extends AbstractType
{
    private $project;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->project = $options['project'];

        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('assignee', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'email'
            ])
            
            ->add('endDate', DateType::class,[
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',

            ])       
            ->add('status', ChoiceType::class,[
                'choices' => [
                    'In Progress' => 1,
                    'Done' => 2,
                    'StandBy' => 3,
            ],
            ]
            )            
            ->add('priority', ChoiceType::class,[
                'choices' => [
                    'Low' => 1,
                    'Medium' => 2,
                    'High' => 3,
            ],
            ]
            )    
            ->add('ajouter', SubmitType::class)       
            //->add('project')
            ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'project' => null,
        ]);
    }
}
