<?php

//namespace App\Helpers;

use App\Permission;


	function isAllowed($oUser, $sPermissionCode){

		$iPerCount = Permission::with(['roles.users' => function($oQuery) use ($oUser){
                            $oQuery->where('id', $oUser->id);
                        }])
                        ->where('permission_code', $sPermissionCode)
                        ->where('is_active', 1)
                        ->where('type', 'allow')
                        ->count();

        //dd($aPermission);

        if($iPerCount > 0)
            return true;

        return false;
	}
