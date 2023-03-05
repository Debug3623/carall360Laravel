<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run() {


        // List of roles 
        $adminRoleDate['name'] = env('SUPER_ADMIN_ROLE_NAME',"Admin");
        $adminRole = Role::updateOrCreate(['name' => $adminRoleDate],$adminRoleDate);

        $doctorRoleData['name'] = env('DOCTOR_ADMIN','Doctor');
        $doctorRole = Role::updateOrCreate(['name' => $doctorRoleData],$doctorRoleData);

        $organizationRoleData['name'] =env('ORGANIZATION_PORTAL','Organization');
        $organizationRole = Role::updateOrCreate(['name' => $organizationRoleData],$organizationRoleData);
       
        $patientRoleData['name'] = env('PATIENT_PORTAL','Patient');
        $patientRole = Role::updateOrCreate(['name' => $patientRoleData],$patientRoleData);

        $parentPermissions = [
            "Roles",
            "Permissions",
            "Menu",
            "Patient",
            "Organizations",
            "Users",
            "Doctors",
            "Treatments",
            "Diagnostics",
            "Medicines",
            "Trash"
        ];
        $prsArray = [
            [
                'role-index',
                'role-create',
                'role-store',
                'role-show',
                'role-update',
                'role-destroy',
                'role-deleted',
                'role-restore',
                'role-delete',
                'role-create-permission',
            ],[
                'permission-index',
                'permission-create',
                'permission-store',
                'permission-show',
                'permission-update',
                'permission-destroy',
                'permission-deleted',
                'permission-restore',
                'permission-delete',
                'parent-permission',
            ],[
                'menu-index',
                'menu-create',
                'menu-store',
                'menu-show',
                'menu-update',
                'menu-destroy',
                'menu-deleted',
                'menu-restore',
                'menu-delete',
            ],[
                'patient-index',
                'patient-create',
                'patient-store',
                'patient-show',
                'patient-update',
                'patient-destroy',
                'patient-deleted',
                'patient-restore',
                'patient-delete',
            ],[
                'organization-index',
                'organization-create',
                'organization-store',
                'organization-show',
                'organization-update',
                'organization-destroy',
                'organization-deleted',
                'organization-restore',
                'organization-delete',
                'create-organization-permissions'
            ],[
                'user-index',
                'user-create',
                'user-store',
                'user-show',
                'user-update',
                'user-destroy',
                'user-deleted',
                'user-restore',
                'user-delete',
                'user-create-role',
                "user-assign-role",
            ],[
                'doctor-index',
                'doctor-create',
                'doctor-store',
                'doctor-show',
                'doctor-update',
                'doctor-destroy',
                'doctor-deleted',
                'doctor-restore',
                'doctor-delete',
                'doctor-assistant-index',
                'doctor-assistant-create',
                'doctor-assistant-store',
                'doctor-assistant-show',
                'doctor-assistant-update',
                'doctor-assistant-destroy',
                'doctor-assistant-deleted',
                'doctor-assistant-restore',
                'doctor-assistant-delete',
                'doctor-award-index',
                'doctor-award-create',
                'doctor-award-store',
                'doctor-award-show',
                'doctor-award-update',
                'doctor-award-destroy',
                'doctor-award-deleted',
                'doctor-award-restore',
                'doctor-award-delete',
                'doctor-certification-index',
                'doctor-certification-create',
                'doctor-certification-store',
                'doctor-certification-show',
                'doctor-certification-update',
                'doctor-certification-destroy',
                'doctor-certification-deleted',
                'doctor-certification-restore',
                'doctor-certification-delete',
                'doctor-experience-index',
                'doctor-experience-create',
                'doctor-experience-store',
                'doctor-experience-show',
                'doctor-experience-update',
                'doctor-experience-destroy',
                'doctor-experience-deleted',
                'doctor-experience-restore',
                'doctor-experience-delete',
                'doctor-qualification-index',
                'doctor-qualification-create',
                'doctor-qualification-store',
                'doctor-qualification-show',
                'doctor-qualification-update',
                'doctor-qualification-destroy',
                'doctor-qualification-deleted',
                'doctor-qualification-restore',
                'doctor-qualification-delete',
                'all-treatments',
                'doctor-treatment-index',
                'doctor-treatment-create',
                'doctor-treatment-store',
                'doctor-treatment-show',
                'doctor-treatment-update',
                'doctor-treatment-destroy',
                'doctor-treatment-deleted',
                'doctor-treatment-restore',
                'doctor-treatment-delete',
                'doctor-center-index',
                'doctor-center-create',
                'doctor-center-store',
                'doctor-center-show',
                'doctor-center-update',
                'doctor-center-destroy',
                'doctor-center-deleted',
                'doctor-center-restore',
                'doctor-center-delete',
                'all-medicines',
                'doctor-medicine-index',
                'doctor-medicine-create',
                'doctor-medicine-store',
                'doctor-medicine-show',
                'doctor-medicine-update',
                'doctor-medicine-destroy',
                'doctor-medicine-deleted',
                'doctor-medicine-restore',
                'doctor-medicine-delete',
                'web-setting-index',
                'web-setting-create',
                'web-setting-store',
                'web-setting-show',
                'web-setting-update',
                'web-setting-destroy',
                'web-setting-deleted',
                'web-setting-restore',
                'web-setting-delete',
                'doctor-schedule-create', 
                'doctor-schedule-store',  
                'doctor-schedule-index',  
                'doctor-schedule-show',   
                'doctor-schedule-update',  
                'doctor-schedule-destroy',
                'doctor-schedule-deleted',
                'doctor-schedule-restore',
                'doctor-schedule-delete',
                'schedule-slot-create', 
                'schedule-slot-store',  
                'schedule-slot-index',  
                'schedule-slot-show',   
                'schedule-slot-update',  
                'schedule-slot-destroy',
            ],[
                'treatment-index',
                'treatment-create',
                'treatment-store',
                'treatment-show',
                'treatment-update',
                'treatment-destroy',
                'treatment-deleted',
                'treatment-restore',
                'treatment-delete',
                'parent-treatment-index',
            ],[
                'diagnostic-create',
                'diagnostic-store',
                'diagnostic-index',
                'diagnostic-show',
                'diagnostic-update',
                'diagnostic-destroy',
                'diagnostic-deleted',
                'diagnostic-restore',
                'diagnostic-delete',
            ],[
                'medicine-index',
                'medicine-create',
                'medicine-store',
                'medicine-show',
                'medicine-update',
                'medicine-destroy',
                'medicine-deleted',
                'medicine-restore',
                'medicine-delete',
            ],
            [
                "Deleted-Roles",
                "Deleted-Permissions",
                "Deleted-Organizations",
                "Deleted-Users",
                "Deleted-Doctors",
                "Deleted-Treatments",
                "Deleted-Medicines",
            ]
        ];
        $i = 0;
        foreach($parentPermissions as $parentPermission){
            $parent =null;
            $prs =[];
            $data =null;

            $data['title'] = $parentPermission;
            $data['permission_code'] = $parentPermission;
            $data['url'] = $parentPermission;
            $data['selected_menu'] = $i+1;
            $data['is_menu'] = 1;
            $parent = Permission::updateOrCreate([
                'permission_code' => $data['permission_code']],$data
            );
            foreach ($prsArray[$i] as $p) {
                $subData = null;
                
                $title = str_replace('-', " ", $p);
                $subData['title'] = $title;
                $subData['permission_code'] = $p;
                $subData['url'] = $p;
                $subData['parent_id'] = $parent->id;
                $prs[] = Permission::updateOrCreate([
                    'permission_code' => $p],$subData
                );
            }
            foreach ($prs as $pr) {
                DB::table('permission_role')->insert([
                    'permission_id' =>$pr->id,
                    'role_id' =>$adminRole->id,
                ]);
            }
            $i++;
        }

        // permissions for Doctor
        $parentPermissions = [
            "Doctors",
            "Assistants",
            "Awards",
            "Certification",
            "Experience",
            "Qualification",
            "Treatments",
            "Centers",
            "Medicine",
            "Web Setting",
            "Schedule",
            "Patient",
        ];
        $prsArray = [
            [
                'doctor-show',
                'doctor-update',
            ],
            [
                'doctor-assistant-index',
                'doctor-assistant-create',
                'doctor-assistant-store',
                'doctor-assistant-show',
                'doctor-assistant-update',
                'doctor-assistant-destroy',
                'doctor-assistant-deleted',
                'doctor-assistant-restore',
                'doctor-assistant-delete',
            ],
            [
                'doctor-award-index',
                'doctor-award-create',
                'doctor-award-store',
                'doctor-award-show',
                'doctor-award-update',
                'doctor-award-destroy',
                'doctor-award-deleted',
                'doctor-award-restore',
                'doctor-award-delete',
            ],
            [
                'doctor-certification-index',
                'doctor-certification-create',
                'doctor-certification-store',
                'doctor-certification-show',
                'doctor-certification-update',
                'doctor-certification-destroy',
                'doctor-certification-deleted',
                'doctor-certification-restore',
                'doctor-certification-delete',
            ],
            [
                'doctor-experience-index',
                'doctor-experience-create',
                'doctor-experience-store',
                'doctor-experience-show',
                'doctor-experience-update',
                'doctor-experience-destroy',
                'doctor-experience-deleted',
                'doctor-experience-restore',
                'doctor-experience-delete',
            ],
            [
                'doctor-qualification-index',
                'doctor-qualification-create',
                'doctor-qualification-store',
                'doctor-qualification-show',
                'doctor-qualification-update',
                'doctor-qualification-destroy',
                'doctor-qualification-deleted',
                'doctor-qualification-restore',
                'doctor-qualification-delete',
            ],
            [
                'all-treatments',
                'doctor-treatment-index',
                'doctor-treatment-create',
                'doctor-treatment-store',
                'doctor-treatment-show',
                'doctor-treatment-update',
                'doctor-treatment-destroy',
                'doctor-treatment-deleted',
                'doctor-treatment-restore',
                'doctor-treatment-delete',
            ],
            [
                'doctor-center-index',
                'doctor-center-create',
                'doctor-center-store',
                'doctor-center-show',
                'doctor-center-update',
                'doctor-center-destroy',
                'doctor-center-deleted',
                'doctor-center-restore',
                'doctor-center-delete',
            ],
            [
                'all-medicines',
                'doctor-medicine-index',
                'doctor-medicine-create',
                'doctor-medicine-store',
                'doctor-medicine-show',
                'doctor-medicine-update',
                'doctor-medicine-destroy',
                'doctor-medicine-deleted',
                'doctor-medicine-restore',
                'doctor-medicine-delete',
            ],
            [
                'web-setting-index',
                'web-setting-create',
                'web-setting-store',
                'web-setting-show',
                'web-setting-update',
                'web-setting-destroy',
                'web-setting-deleted',
                'web-setting-restore',
                'web-setting-delete',
            ],
            [
                'doctor-schedule-create', 
                'doctor-schedule-store',  
                'doctor-schedule-index',  
                'doctor-schedule-show',   
                'doctor-schedule-update',  
                'doctor-schedule-destroy',
                'doctor-schedule-deleted',
                'doctor-schedule-restore',
                'doctor-schedule-delete',
                'schedule-slot-create', 
                'schedule-slot-store',  
                'schedule-slot-index',  
                'schedule-slot-show',   
                'schedule-slot-update',  
                'schedule-slot-destroy',
            ],[
                'patient-index',
                'patient-create',
                'patient-store',
                'patient-show',
                'patient-update',
                'patient-destroy',
                'patient-deleted',
                'patient-restore',
                'patient-delete',
            ],
        ];
        $j = 0;
        foreach($parentPermissions as $parentPermission){
            $parent =null;
            $prs =[];
            $data =null;

            $data['title'] = $parentPermission;
            $data['permission_code'] = $parentPermission;
            $data['url'] = $parentPermission;
            $parent = Permission::updateOrCreate([
                'permission_code' => $data['permission_code']],$data
            );
            foreach ($prsArray[$j] as $p) {
                $subData = null;

                $title = str_replace('-', " ", $p);
                $subData['title'] = $title;
                $subData['permission_code'] = $p;
                $subData['url'] = $p;
                $subData['parent_id'] = $parent->id;
                $prs[] = Permission::updateOrCreate([
                    'permission_code' => $p],$subData
                );
            }
            foreach ($prs as $pr) {
                DB::table('permission_role')->insert([
                    'permission_id' =>$pr->id,
                    'role_id' =>$doctorRole->id,
                ]);
            }
            $j++;
        }
    }
}
