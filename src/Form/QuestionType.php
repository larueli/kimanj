<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array("label" => "Un nom court pour votre question",
                                                  "attr"  => array("placeholder" => "Kimanj, Navette d'adrien, ...")))
            ->add('interrogation', TextareaType::class, array("label" => "La question compléte",
                                                              "attr"  => array("placeholder" => "Kimanj, La navette d'adrien part...")))
            ->add("estRAZQuotidien", CheckboxType::class, array("required" => false,
                                                                "label"    => "Les réponses sont effacées tous les jours à " . $_ENV[ 'HEURE_RAZ' ]))
            ->add("estVisible", CheckboxType::class,
                  array("required" => false, "label" => "La question est visible des autres utilisateurs"))
            ->add('reponsesPubliques', CheckboxType::class,
                  array("required" => false, "label" => "Les résultats sont publics",
                        "help"     => "Tout le monde peut voir le nombre de voix, et éventuellement les noms si non anonyme. Si vous passez de privé à public, les réponses sont effacées"))
            ->add("choixMultiple", CheckboxType::class,
                  array("required" => false, "label" => "Plusieurs choix possibles par personne"))
            ->add("reponsesAnonymes", CheckboxType::class, array("required" => false, "label" => "Réponses anonymes",
                                                                 "help"     => "N'affichera que le nombre de voix. Si résultats non publics, seul vous pourrez voir le nombre de voix. Si vous passez d'anonyme à non anonyme, les réponses sont effacées"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => Question::class,
                               ]);
    }
}
