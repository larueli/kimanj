<?php


namespace App\Form\DataTransformer;

use App\Entity\Reponse;
use Symfony\Component\Form\DataTransformerInterface;


class ChoixUniqueTransformer implements DataTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (empty($value))
            return $value;
        return $value->getValues()[ 0 ];
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        $reponse = new Reponse();
        $reponse->addChoix($value);
        return $reponse->getChoix();
    }
}