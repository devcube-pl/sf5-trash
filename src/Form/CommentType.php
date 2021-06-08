<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',
                  TextareaType::class,
                  [
                      'label' => 'Wpisz treść komentarza',
                      'help' => 'Komentarze nie spełniające naszych wyśrubowanych standardów zostaną usunięte!!!'
                  ]
            );
    }
}
