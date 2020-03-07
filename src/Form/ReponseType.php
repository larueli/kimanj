<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\ChoixPossible;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\DataTransformer\ChoixUniqueTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReponseType extends AbstractType
{
    private $entityManager;
    private $transformer;

    /**
     * ReponseType constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ChoixUniqueTransformer $transformer
     */
    public function __construct(EntityManagerInterface $entityManager, ChoixUniqueTransformer $transformer)
    {
        $this->entityManager = $entityManager;
        $this->transformer   = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choix', EntityType::class,
                  array("mapped"                                                                      => true,
                        "class"                                                                       => ChoixPossible::class,
                        "required"                                                                    => true,
                        "multiple"                                                                    => $options[ "question" ]->getChoixMultiple(),
                        "expanded"                                                                    => $options[ "question" ]->getChoixMultiple(),
                        "choices"                                                                     => $this->entityManager->getRepository(ChoixPossible::class)
                            ->findBy(["question" => $options[ "question" ]->getId()]), "choice_label" => "texte"))
            ->add('commentaire', TextareaType::class, array("required" => false, "label" => "Un commentaire ?",
                                                            "attr"     => array("placeholder" => "Je serai à la bourre, ...")));

        if ( !$options[ "question" ]->getChoixMultiple()) {
            $builder->get('choix')->addModelTransformer($this->transformer);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => Reponse::class,
                                   "question"   => ( new Question() ),
                               ]);
    }
}
