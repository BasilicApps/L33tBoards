<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Board;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;

class PostFormType extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $boards = $this->security->getUser()->getFollowedBoards();
        $mBoards = [];

        for ($i = 0; $i < count($boards); $i++)
            $mBoards[$boards[$i]->getTitle()] = $boards;

        $builder
            ->add('board', EntityType::class, [
                'class' => Board::class,
                'choices' => $boards,
                'choice_label' => 'title'
            ])
            ->add('title')
            ->add('content')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
