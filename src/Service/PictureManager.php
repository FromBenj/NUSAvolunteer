<?php

namespace App\Service;

use App\Entity\Organisation;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureManager
{
    private ParameterManager $parameters;

    public function __construct(ParameterManager $parameters) {
        $this->parameters = $parameters;
    }

    public function getUniqueName(string $pictureName): string
    {
        $extension = pathinfo($pictureName, PATHINFO_EXTENSION);
        $extensionLength = strlen($extension) + 1;
        $name = substr($pictureName, 0, - $extensionLength);
        $cleanName = preg_replace('/[^a-zA-Z0-9_\-]/', 'x', $name);

        return uniqid($cleanName, false) . '.' . $extension;
    }

    public function checkOrganisationPictures(Organisation $organisation): void
    {
        if (!$organisation->getavatarName()) {
            $defaultAvatarName = $this->parameters->getOrganisationImagesParameter() . "/avatars/" . "default_avatarName" . "jpg";
            $organisation->setAvatarName($defaultAvatarName);
        }
        if ( !$organisation->getActivityPictureName()) {
            $defaultActivityPictureName = $this->parameters->getOrganisationImagesParameter() . "/activity-pictures/" . "default_activityPictureName" . "jpg";
            $organisation->setActivityPictureName($defaultActivityPictureName);
        }
        if (!$organisation->getRepresentativePictureName()) {
                $defaultRepresentativePictureName = $this->parameters->getOrganisationImagesParameter() . "/representative-pictures/" . "default_representativePictureName" . "jpg";
                $organisation->setRepresentativePictureName($defaultRepresentativePictureName);
        }
    }
}
