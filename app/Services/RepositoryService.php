<?php 

namespace App\Services;

use App\Models\Repository;

class RepositoryService
{
    public function createRepository($data)
    {
        return Repository::create($data);
    }

    public function updateRepository($repositoryID, $data)
    {
        $repository = Repository::findOrFail($repositoryID);
        $repository->update($data);
        return true;
    }

    public function deleteRepository($repositoryID)
    {
        $repository = Repository::findOrFail($repositoryID);
        $repository->delete();
        return true;
    }

    public function getRepository($repositoryID)
    {
        return Repository::findOrFail($repositoryID);
    }
}