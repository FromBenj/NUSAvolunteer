<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('organisation-prospects')]
class OrganisationProspects
{
    public Array|null $matchings;
    public bool|null $lastMessage = null;
    public bool|null $needAcceptation = null;
}
