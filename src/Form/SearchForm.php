<?php


namespace App\Form;


use App\Data\SearchData;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use function PHPSTORM_META\map;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => "Une idée ?",
                'required' => false,
                'attr' => [
                    'placeholder' => 'ex : Peugeot'
                ]
            ])
            ->add('make', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Annonce::class,
                'choice_label' => 'make',
                'choice_value' => function(Annonce $annonce = null){
                    return $annonce ? strtolower($annonce->returnMake()) : '';
                }

            ])
            ->add('model', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Annonce::class,
                'choice_label' => 'model',
                'choice_value' => function(Annonce $annonce = null){
                    return $annonce ? strtolower($annonce->returnModel()) : '';
                }

            ])

            ->add('minPrice', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'min'
                ]
            ])
            ->add('maxPrice', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'max'
                ]
            ])
            ->add('minYear', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'min'
                ]
            ])
            ->add('maxYear', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'max'
                ]
            ])
            ->add('minKilometer', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'min'
                ]
            ])
            ->add('maxKilometer', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'max'
                ]
            ]);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}