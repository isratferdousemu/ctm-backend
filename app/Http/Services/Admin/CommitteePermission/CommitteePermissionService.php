<?php

namespace App\Http\Services\Admin\CommitteePermission;

use App\Models\CommitteePermission;
use App\Models\Lookup;

class CommitteePermissionService
{
    public function getCommitteePermissions()
    {
        return Lookup::whereType(17)
            ->with('committeePermission')
            ->select('id', 'type', 'value_en', 'value_bn')
            ->get();
    }


    public function saveCommitteePermission($request)
    {
        $permission = new CommitteePermission();
        $permission->committee_type_id = $request->committee_type_id;
        $permission->approve = (bool)$request->approve;
        $permission->forward = (bool)$request->forward;
        $permission->reject = (bool)$request->reject;
        $permission->waiting = (bool)$request->waiting;
        $permission->created_by = auth()->id();
        $permission->save();

        return $permission;
    }


    public function deleteByCommitteeType($committeeTypeId)
    {
        return CommitteePermission::where('committee_type_id', $committeeTypeId)->delete();
    }










}


