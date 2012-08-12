<?php
namespace STS\Domain;
use STS\Domain\Entity;

class Presentation extends Entity
{

    private $types = array(
            'med' => 'MED', 'pa' => 'PA', 'np' => 'NP', 'ns' => 'NS', 'resobgyn' => 'RES OBGYN', 'resint' => 'RES INT',
            'other' => 'OTHER'
    );
    public static function getTypes()
    {
        $presentation = new Presentation();
        return $presentation->types;
    }
}
