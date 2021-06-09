<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostType extends AbstractType
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * PostType constructor.
     * @param  SluggerInterface  $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('summary', TextareaType::class)
            ->add('content', null, ['attr' => ['rows' => 20]]);

        if ($options['isHtml']) {
            $builder
                ->add('publishedAt', DateTimePickerType::class)
                ->add('tags', TagsInputType::class, [
                    'required' => false
                ]);
        }

        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
            /**
             * @var $post Post
             */
            $post = $event->getData();

            if (null !== $postTitle = $post->getTitle()) {
                $post->setSlug($this->slugger->slug($postTitle)->lower());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Post::class,
            'csrf_protection' => true,
            'isHtml' => true
        ]);

        $resolver->setAllowedTypes('isHtml', 'bool');
    }
}