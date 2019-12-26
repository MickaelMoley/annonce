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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('make', ChoiceType::class, [
                'required' => false,
                'choices' => $options['makes'],
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($value);

                },
                'choice_value' => function ($value) {


                    return strtolower($value);

                },
                'group_by' => null
            ])
            ->add('model', ChoiceType::class, [
                'required' => false,
                'choices' => $options['models'],
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($value);

                },
                'choice_value' => function ($value) {

                    return strtolower($value);

                },
                'group_by' => null
            ])
            ->add('fuelType', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Gasoline' => 'gasoline',
                    'Diesel' => 'diesel',
                    'Essence' => 'essence'
                ]
            ])
            ->add('bodyStyle', ChoiceType::class, [
                'required' => false,
                'choices' => $options['bodyStyle'],
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($value);

                },
                'choice_value' => function ($value) {

                    return strtolower($value);

                },
                'group_by' => null
            ])
            ->add('transmission', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    ' ' => '',
                    'Manuel' => 'manuel',
                    'Automatique' => 'automatic'
                ]
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
            'makes' => null,
            'models' => null,
            'bodyStyle' => null,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}