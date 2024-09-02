<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureManager
{
    private ParameterBagInterface $parameters;

    public function __construct(ParameterBagInterface $parameters) {
        $this->parameters = $parameters;
    }

    public function getParameters(): ParameterBagInterface
    {
        return $this->parameters;
    }

    public function getUniqueName(string $pictureName): string
    {
        $extension = pathinfo($pictureName, PATHINFO_EXTENSION);
        $extensionLength = strlen($extension) + 1;
        $name = substr($pictureName, 0, - $extensionLength);

        return uniqid($name, false) . '.' . $extension;
    }
}
