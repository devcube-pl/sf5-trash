<?php

namespace App\Form;

use App\Form\DataTransformer\TagArrayToStringTransformer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TagsInputType extends AbstractType
{
    /**
     * @var TagRepository
     */
    private $tags;

    /**
     * TagsInputType constructor.
     * @param  TagRepository  $tags
     */
    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new TagArrayToStringTransformer($this->tags), true);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['tags'] = $this->tags->findAll();
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }
}
