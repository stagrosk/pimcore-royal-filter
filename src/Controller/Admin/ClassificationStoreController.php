<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use OpenDxp\Model\DataObject\Classificationstore\GroupConfig;
use OpenDxp\Model\DataObject\Classificationstore\KeyConfig;
use OpenDxp\Model\DataObject\Classificationstore\KeyGroupRelation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ClassificationStoreController
{
    #[Route('/admin/classification-store/group-key-map', name: 'admin_cs_group_key_map', methods: ['GET'])]
    public function groupKeyMap(): JsonResponse
    {
        $groups = [];
        $groupListing = new GroupConfig\Listing();
        $groupListing->setOrderKey('name');
        $groupListing->setOrder('ASC');
        foreach ($groupListing->getList() as $group) {
            $groups[] = [
                'id' => $group->getId(),
                'name' => $group->getName(),
            ];
        }

        $keys = [];
        $keyListing = new KeyConfig\Listing();
        $keyListing->setOrderKey('name');
        $keyListing->setOrder('ASC');
        foreach ($keyListing->getList() as $key) {
            $keys[] = [
                'id' => $key->getId(),
                'name' => $key->getName(),
            ];
        }

        $relations = [];
        $relationListing = new KeyGroupRelation\Listing();
        foreach ($relationListing->load() as $relation) {
            $relations[] = [
                'groupId' => $relation->getGroupId(),
                'keyId' => $relation->getKeyId(),
            ];
        }

        return new JsonResponse([
            'groups' => $groups,
            'keys' => $keys,
            'relations' => $relations,
        ]);
    }
}
