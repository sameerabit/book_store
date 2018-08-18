<?php
namespace AppBundle\Forms;

use AppBundle\Entity\Book;
use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class,array());
        $builder->add('name',TextType::class);
        $builder->add('description', TextareaType::class);
        $builder->add('unitPrice', NumberType::class);
        $builder->add('author', TextType::class);
        $builder->add('category', EntityType::class, array(
            'class' => Category::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cat')
                    ->orderBy('cat.name,cat.id', 'ASC');
            },
            'choice_label' => 'name',
        ));
        $builder->add('Next', SubmitType::class, array(
            'label' => 'Submit',
            'attr' => ['class' => 'ui button']
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Book::class
        ));
    }

}