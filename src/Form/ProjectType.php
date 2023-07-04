<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,ButtonType,ChoiceType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;
class ProjectType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $loggedInUser = $this->security->getUser();
    
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('end', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('teammates', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true,
                'choice_attr' => function ($user) use ($loggedInUser) {
                    return $user === $loggedInUser ? ['checked' => 'checked'] : [];
                },
                'query_builder' => function (UserRepository $userRepository) use ($loggedInUser) {
                    return $userRepository->createQueryBuilder('u');
                },
                'data' => [$loggedInUser], // Preselect the logged-in user
            ])
        ;
    }
    
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
