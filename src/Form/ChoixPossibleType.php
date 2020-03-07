<?php

namespace App\Form;

use App\Entity\ChoixPossible;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChoixPossibleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('texte', TextType::class, array("label" => "Indiquez la réponse possible"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => ChoixPossible::class,
                               ]);
    }
}
