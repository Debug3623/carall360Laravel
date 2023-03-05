<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CareAll360 Application messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the message library to build
    | the simple messages. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */


    'general' => [
        'exception' => 'Unexpcted error found, please try again.',
        'validation_fail' => 'Validation on user input data failed.',
        'created' => ':mod created successfully.',
        'update' => ':mod updated successfully.',
        'delete' => ':mod deleted successfully.',
        'reschedule' => ':mod Rescheduled successfully.',
        'followOn' => ':mod added as Follow up successfully.',
        'list'   => 'List of all :mod',
        'detail' => ':mod detail',
        'deletedList' => 'List of deleted :mod',
        'restore' => ':mod restored successfully.',
        'permanentDelete' => ':mod deleted permanently successfully.',
        'already' => 'The :mod has already been taken.',
        'notFind' => 'Sorry, We did not find your record.'
    ],

    'organization'=> [
        'create' => 'Organization created successfully.',
        'update' => 'Organization updated successfully.',
        'delete' => 'Organization deleted successfully.',
        'list'   => 'List of all Organizations',
        'detail' => 'Organization detail',
        'deletedList' => 'List of deleted Organization',
        'restore' => 'Organization restored successfully.',
        'permanentDelete' => 'Organization deleted permanently successfully.',
        'createOrganizationPermission' => 'Permissions assigned to organization successfully',
    ],
    'permissions'=> [
        'create' => 'Permissions created successfully.',
        'update' => 'Permissions updated successfully.',
        'delete' => 'Permissions deleted successfully.',
        'list'   => 'List of all Permissions',
        'detail' => 'Permissions detail',
        'deletedList' => 'List of deleted Permissions',
        'restore' => 'Permission restored successfully.',
        'permanentDelete' => 'Permission deleted permanently successfully.',
        'parentList' => 'List of Parent Permission',
    ],
    'roles'=> [
        'create' => 'Role created successfully.',
        'update' => 'Role updated successfully.',
        'delete' => 'Role deleted successfully.',
        'list'   => 'List of all Roles',
        'detail' => 'Role detail',
        'deletedList' => 'List of deleted Roles',
        'restore' => 'Role restored successfully.',
        'permanentDelete' => 'Role deleted permanently successfully.',
        'createRolePermission' => 'Permissions assigned to role successfully',
    ],
    'users'=> [
        'create' => 'User created successfully.',
        'update' => 'User updated successfully.',
        'delete' => 'User deleted successfully.',
        'list'   => 'List of all Users',
        'detail' => 'User detail',
        'deletedList' => 'List of deleted Users',
        'restore' => 'Users restored successfully.',
        'permanentDelete' => 'User deleted permanently successfully.',
        'createUserRole' => 'Role assigned to User successfully',
    ],
    'city'=> [
        'create' => 'City created successfully.',
        'update' => 'City updated successfully.',
        'delete' => 'City deleted successfully.',
        'list'   => 'List of all Cities',
        'detail' => 'City detail',
        'deletedList' => 'List of deleted City',
        'restore' => 'City restored successfully.',
        'permanentDelete' => 'City deleted permanently successfully.',
    ],
    'country'=> [
        'create' => 'Country created successfully.',
        'update' => 'Country updated successfully.',
        'delete' => 'Country deleted successfully.',
        'list'   => 'List of all Countries',
        'detail' => 'Country detail',
        'deletedList' => 'List of deleted Country',
        'restore' => 'Country restored successfully.',
        'permanentDelete' => 'Country deleted permanently successfully.',
    ],

    'patient' => [
        'signup-doctor-inactive' => 'Doctor is inactive or not available in system.', 
        'signup-success' => 'Patient signup successfully.:code',
        'signup-already' => 'Patient already signed up, please verify mobile number.:code',


    ],
    'treatments' => [
        'create' => 'Treatment created successfully.',
        'update' => 'Treatment updated successfully.',
        'delete' => 'Treatment deleted successfully.',
        'list'   => 'List of all Treatments',
        'detail' => 'Treatment detail',
        'deletedList' => 'List of deleted Treatments',
        'restore' => 'Treatment restored successfully.',
        'permanentDelete' => 'Treatment deleted permanently successfully.',
    ],


    'EMR' => [
        'noaccess' => 'Content trying to view, update or create not allowed',
        'create' => 'Medical record uploaded successfully',
        'update' => 'Medical record updated successfully',
        'list' => 'List of all medical records',
        'view' => 'Medical record viewed by user',
        'update' => 'Medical record updated',
        'delete' => 'Medical record permanently deleted',
        'inactive' => 'Record to view is not active by patient'


    ],
    'schedule'=>[
        'already'     => "Select another date to continue. Schedule already created",
        'appointment' => "You are not allow to update schedule because Appointment is scheduled",
        'previous'    => "Selected time has passed, please select another slot!",
    ],
     'bankaccount'=> [
        'create' => 'Bank Account created successfully.',
        'update' => 'Bank Account updated successfully.',
        'delete' => 'Bank Account deleted successfully.',
        'list'   => 'List of all Bank Accounts',
        'detail' => 'Bank Account detail',
        'deletedList' => 'List of deleted Roles',
        'restore' => 'Bank Account restored successfully.',
        'permanentDelete' => 'Bank Account deleted permanently successfully.',
        'createRolePermission' => 'Permissions assigned to bank account successfully',
    ],
    'weeks_fee_frequency' => 'Fee Frequancy should be less then or equal to 2 otherwise use months option',
    'months_fee_frequency' => 'Fee Frequancy should be less then or equal to 12',
];