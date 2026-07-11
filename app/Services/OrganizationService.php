<?php

namespace App\Services;

use App\Enum\MemberRoles;
use App\Models\Oragnization;
use App\Models\OrganizationMember;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationService
{
    public function createOrganization(array $data): void
    {
        $userID = Auth::id();
        DB::transaction(function () use ($data, $userID) {
            $organization = Oragnization::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'logo' => $data['logo'] ?? null,
                'description' => $data['description'] ?? null,
                'owner_id' => $userID,
            ]);

            OrganizationMember::create([
                'organization_id' => $organization->id,
                'user_id' => $userID,
                'role' => MemberRoles::OWNER,
                'joined_at' => now(),
            ]);
        });
    }

    public function updateOrganization($organizationID, array $data): void
    {
        $userID = Auth::id();
        $organization = Oragnization::where('id', $organizationID)->firstOrFail();
        if ($organization->owner_id !== $userID) throw new Exception('You are not authorized to update this organization.');

        $organization->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'logo' => $data['logo'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function inviteMember($organizationID, $userID, $role): void
    {
        $organization = Oragnization::where('id', $organizationID)->firstOrFail();
        if ($organization->owner_id !== $userID) throw new Exception('You are not authorized to update this organization.');
        if (!in_array($role, MemberRoles::getRoles())) throw new Exception('Invalid role specified.');
        OrganizationMember::create([
            'organization_id' => $organization->id,
            'user_id' => $userID,
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    public function removeMember($organizationID, $userID): void
    {
        $organization = Oragnization::where('id', $organizationID)->firstOrFail();
        if ($organization->owner_id !== $userID) throw new Exception('You are not authorized to update this organization.');
        $member = OrganizationMember::where([['organization_id', $organizationID], ['user_id', $userID]])->firstOrFail();
        if ($member->role === MemberRoles::OWNER) throw new Exception('Cannot remove the owner of the organization.');
        $member->delete();
    }

    public function leaveOrganization($organizationID): void
    {
        Oragnization::where('id', $organizationID)->firstOrFail();
        $member = OrganizationMember::where([['organization_id', $organizationID], ['user_id', Auth::id()]])->firstOrFail();
        $member->delete();
    }

    public function changeMemberRole($organizationID, $userID, $newRole): void
    {
        $organization = Oragnization::where('id', $organizationID)->firstOrFail();
        if ($organization->owner_id !== Auth::id()) throw new Exception('You are not authorized to update this organization.');
        if (!in_array($newRole, MemberRoles::getRoles())) throw new Exception('Invalid role specified.');
        $member = OrganizationMember::where([['organization_id', $organizationID], ['user_id', $userID]])->firstOrFail();
        if ($member->role === MemberRoles::OWNER) throw new Exception('Cannot change the role of the owner of the organization.');
        $member->update(['role' => $newRole]);
    }

    public function getOrganizations(): object
    {
        $userID = Auth::id();
        $organizations = Oragnization::with('owner')->where('owner_id', $userID)
            ->orWhereHas('users', function ($query) use ($userID) {
                $query->where('user_id', $userID);
            })->get();
        return $organizations;
    }

    public function getOrganizationMembers($organizationID): object
    {
        $organization = Oragnization::where('id', $organizationID)->exists();
        if (!$organization) throw new Exception('Organization not found.');
        return OrganizationMember::where('organization_id', $organizationID)->with('user')->get();
    }

    public function deleteOrganization($organizationID): void
    {
        $organization = Oragnization::where('id', $organizationID)->firstOrFail();
        if ($organization->owner_id !== Auth::id()) throw new Exception('You are not authorized to delete this organization.');
        $organization->delete();
    }
}
