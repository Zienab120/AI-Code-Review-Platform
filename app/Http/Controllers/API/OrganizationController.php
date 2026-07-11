<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Oragnization\CreateOrganizationRequest;
use App\Http\Requests\Oragnization\InviteMemberRequest;
use App\Http\Resources\Organization\OrganizationResource;
use App\Services\OrganizationService;
use App\Traits\ApiResponseTrait;

class OrganizationController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected OrganizationService $organizationService) {}

    public function create(CreateOrganizationRequest $request)
    {
        $this->organizationService->createOrganization($request->validated());
        return $this->successResponse('Organization created successfully.');
    }

    public function update($organizationID, CreateOrganizationRequest $request)
    {
        $this->organizationService->updateOrganization($organizationID, $request->validated());
        return $this->successResponse('Organization updated successfully.');
    }

    public function delete($organizationID)
    {
        $this->organizationService->deleteOrganization($organizationID);
        return $this->successResponse('Organization deleted successfully.');
    }

    public function inviteMember($organizationID, InviteMemberRequest $request)
    {
        $this->organizationService->inviteMember($organizationID, $request->input('user_id'), $request->input('role'));
        return $this->successResponse('Member invited successfully.');
    }

    public function removeMember($organizationID, $userID)
    {
        $this->organizationService->removeMember($organizationID, $userID);
        return $this->successResponse('Member removed successfully.');
    }

    public function leaveOrganization($organizationID)
    {
        $this->organizationService->leaveOrganization($organizationID);
        return $this->successResponse('You left the organization successfully.');
    }

    public function changeMemberRole($organizationID, $userID, $newRole)
    {
        $this->organizationService->changeMemberRole($organizationID, $userID, $newRole);
        return $this->successResponse('Member role changed successfully.');
    }

    public function index()
    {
        $organizations = $this->organizationService->getOrganizations();
        return $this->successResponse(OrganizationResource::collection($organizations));
    }

    public function getOrganizationMembers($organizationID)
    {
        $members = $this->organizationService->getOrganizationMembers($organizationID);
        return $this->successResponse($members);
    }
}
