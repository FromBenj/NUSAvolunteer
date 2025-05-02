<?php

namespace App\Service;

use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;

class PictureManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        if( !$organisation->getavatarName() || !$organisation->getActivityPictureName() || !$organisation->getRepresentativePictureName()) {
            if (!$organisation->getavatarName()) {
                $defaultAvatarName = "default_avatarName.jpg";
                $organisation->setAvatarName($defaultAvatarName);
            }
            if (!$organisation->getActivityPictureName()) {
                $defaultActivityPictureName = "default_activityPictureName.jpg";
                $organisation->setActivityPictureName($defaultActivityPictureName);
            }
            if (!$organisation->getRepresentativePictureName()) {
                $defaultRepresentativePictureName = "default_representativePictureName.jpg";
                $organisation->setRepresentativePictureName($defaultRepresentativePictureName);
            }
            $this->entityManager->flush();
        }
    }
}
