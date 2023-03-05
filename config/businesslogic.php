<?php

return [
/*
    |--------------------------------------------------------------------------
    | CliniCALL API Documentation
    |--------------------------------------------------------------------------
    |
    | This array includes module names and menu list under each modules. module name and title should be unique .
    | this will return array that will use to render API documentation 
    |
    */

    'default_token_expiry' => 21600, //15 days in mins (after which if token not used expired)


    // Do not show roles in role list when create and updating new User from admin.
    "notAllowRoles" => [
        "Doctor",
        "Patient"
    ],

    0 =>[   'title'=>"Organization",
            'menu' =>  [
                'All organizations',                //0
                'Create organization',              //1
                'Show organization detail',         //2
                'Update organization',              //3
                'Delete organization',              //4
                'Deleted organizations',            //5
                'Restore organization',             //6
                'Permanent Delete organization',    //7
                'Create Organization Permissions'   //8
            ],
        ],
    1 =>[   'title'=>"Permissions",
            'menu' =>  [
                'All permissions',                  //0
                'Create permissions',               //1
                'Show permissions detail',          //2
                'Update permissions',               //3
                'Delete permissions',               //4
                'Deleted permissions',              //5
                'Restore permission',               //6
                'Permanent Delete permission',      //7
                'Parent permissions',               //8
            ],
        ],
    2 =>[   'title'=>"Roles",
            'menu' =>  [
                'All roles',                       //0
                'Create roles',                    //1
                'Show roles detail',               //2
                'Update roles',                    //3
                'Delete roles',                    //4
                'Deleted roles',                   //5
                'Restore role',                    //6
                'Permanent Delete role',           //7
                'Permissions assigned to role',    //8
                'Dont Show Role',                  //9
            ],
        ],
    3 => [
            'title' => 'Patients-self-service',
            'menu' => [
                    'sign up',
                    'Phone number not verified',
                    'Email not verified',
                ]
            ],
    4 =>[   'title'=>"City",
            'menu' =>  [
                'All Cities',                       //0
                'Create City',                      //1
                'Show City detail',                 //2
                'Update City',                      //3
                'Delete Citys',                     //4
                'Deleted Citys',                    //5
                'Restore Citys',                    //6
                'Permanent Delete City',            //7
            ],
        ],
    5 =>[   'title'=>"Country",
            'menu' =>  [
                'All Countries',                       //0
                'Create Country',                      //1
                'Show Country detail',                 //2
                'Update Country',                      //3
                'Delete Countries',                    //4
                'Deleted Countries',                   //5
                'Restore Countries',                   //6
                'Permanent Delete Country',            //7
            ],
        ],
    6 =>[   'title'=>"Doctor Profile",
            'menu' =>  [
                'All Doctors',                          //0
                'Create Doctor',                        //1
                'Show Doctor detail',                   //2
                'Update Doctor',                        //3
                'Delete Doctors',                       //4
                'Deleted Doctors',                      //5
                'Restore Doctors',                      //6
                'Permanent Delete Doctor',              //7
                'Doctor Landing Page',                  //8
                'List Of Doctors',                      //9
            ],
        ],
    7 =>[   'title'=>"Treatments",
            'menu' =>  [
                'All treatments',                       //0
                'Create treatments',                    //1
                'Show treatments detail',               //2
                'Update treatments',                    //3
                'Delete treatments',                    //4
                'Deleted treatments',                   //5
                'Restore treatments',                   //6
                'Permanent Delete treatments'           //7
            ],
        ],
    8 =>[   'title'=>"Users",
            'menu' =>  [
                'All Users',                        //0
                'Create User',                      //1
                'Show User detail',                 //2
                'Update User',                      //3
                'Delete Users',                     //4
                'Deleted Users',                    //5
                'Restore Users',                    //6
                'Permanent Delete User',            //7
                'Role assigned to User',            //8
            ],
        ],
    9 =>[   'title'=>"Doctor Assistant",
            'menu' =>  [
                'All Assistant',                    //0
                'Create Assistant',                 //1
                'Show Assistant detail',            //2
                'Update Assistant',                 //3
                'Delete Assistant',                 //4
                'Deleted Assistant',                //5
                'Restore Assistant',                //6
                'Permanent Delete Assistant',       //7
            ],
        ],
    10 =>[   'title'=>"Doctor Award",
            'menu' =>  [
                'All Award',                    //0
                'Create Award',                 //1
                'Show Award detail',            //2
                'Update Award',                 //3
                'Delete Award',                 //4
                'Deleted Award',                //5
                'Restore Award',                //6
                'Permanent Delete Award',       //7
            ],
        ],
    11 =>[   'title'=>"Doctor Experience",
            'menu' =>  [
                'All Experience',                    //0
                'Create Experience',                 //1
                'Show Experience detail',            //2
                'Update Experience',                 //3
                'Delete Experience',                 //4
                'Deleted Experience',                //5
                'Restore Experience',                //6
                'Permanent Delete Experience',       //7
            ],
        ],
    12 =>[   'title'=>"Doctor Certification",
            'menu' =>  [
                'All Certification',                    //0
                'Create Certification',                 //1
                'Show Certification detail',            //2
                'Update Certification',                 //3
                'Delete Certification',                 //4
                'Deleted Certification',                //5
                'Restore Certification',                //6
                'Permanent Delete Certification',       //7
            ],
        ],
    13 =>[   'title'=>"Doctor Qualification",
            'menu' =>  [
                'All Qualification',                    //0
                'Create Qualification',                 //1
                'Show Qualification detail',            //2
                'Update Qualification',                 //3
                'Delete Qualification',                 //4
                'Deleted Qualification',                //5
                'Restore Qualification',                //6
                'Permanent Delete Qualification',       //7
            ],
        ],
    14 =>[   'title'=>"Doctor Treatments",
            'menu' =>  [
                'All Doctor Treatments',                   //0
                'Create Doctor Treatment',                 //1
                'Show Doctor Treatment detail',            //2
                'Update Doctor Treatment',                 //3
                'Delete Doctor Treatment',                 //4
                'Deleted Doctor Treatment',                //5
                'Restore Doctor Treatment',                //6
                'Permanent Delete Doctor Treatment',       //7
                'List of All Treatments',                  //8
                'List of All Doctor Treatments',           //9
            ],
        ],
    15 =>[   'title'=>"Treatments",
            'menu' =>  [
                'All Treatments',                    //0
                'Create Treatments',                 //1
                'Show Treatments detail',            //2
                'Update Treatments',                 //3
                'Delete Treatments',                 //4
                'Deleted Treatments',                //5
                'Restore Treatments',                //6
                'Permanent Delete Treatments',       //7
                'Parent Treatments',                 //8
            ],
        ],
    16 =>[   'title'=>"Doctor Medicines",
            'menu' =>  [
                'All Doctor Medicines',              //0
                'Create Doctor Medicine',            //1
                'Show Doctor Medicine detail',       //2
                'Update Doctor Medicine',            //3
                'Delete Doctor Medicine',            //4
                'Deleted Doctor Medicine',           //5
                'Restore Doctor Medicine',           //6
                'Permanent Delete Doctor Medicine',  //7
                'List of All Medicines',             //8
                'List of Doctor All Medicines',      //9
            ],
        ],
    17 =>[   'title'=>"Medicines",
            'menu' =>  [
                'All Medicines',                    //0
                'Create Medicines',                 //1
                'Show Medicines detail',            //2
                'Update Medicines',                 //3
                'Delete Medicines',                 //4
                'Deleted Medicines',                //5
                'Restore Medicines',                //6
                'Permanent Delete Medicines',       //7
            ],
        ],
    18 =>[   'title'=>"Doctor Centers",
            'menu' =>  [
                'All Doctor Centers',              //0
                'Create Doctor Center',            //1
                'Show Doctor Center detail',       //2
                'Update Doctor Center',            //3
                'Delete Doctor Center',            //4
                'Deleted Doctor Center',           //5
                'Restore Doctor Center',           //6
                'Permanent Delete Doctor Center',  //7
            ],
        ],
    19 =>[   'title'=>"Doctor Web Settings",
            'menu' =>  [
                'All Doctor Web Settings',              //0
                'Create Doctor Web Setting',            //1
                'Show Doctor Web Setting detail',       //2
                'Update Doctor Web Setting',            //3
                'Delete Doctor Web Setting',            //4
                'Deleted Doctor Web Setting',           //5
                'Restore Doctor Web Setting',           //6
                'Permanent Delete Doctor Web Setting',  //7
                'Send Email',                           //8
                'List of Social Links',                 //9
            ],
        ],
    20 =>[   'title'=>"Doctor Schedule",
            'menu' =>  [
                'All Doctor Schedules',              //0
                'Create Doctor Schedule',            //1
                'Show Doctor Schedule detail',       //2
                'Update Doctor Schedule',            //3
                'Delete Doctor Schedule',            //4
                'Deleted Doctor Schedule',           //5
                'Restore Doctor Schedule',           //6
                'Permanent Delete Doctor Schedule',  //7
                'Time Slot',                         //8
                'Vocation Mood',                     //9
                'Doctor Time Slots',                 //10
            ],
        ],
    21 =>[   'title'=>"Doctor Schedule Days",
            'menu' =>  [
                'All Schedules Slots',               //0
                'Create Schedule Slot',              //1
                'Show Schedule Slot Detail',         //2
                'Update Schedule Day Slot',          //3
                'Delete Schedule Day Slot',          //4
            ],
        ],
    22 =>[   'title'=>"Menu",
        'menu' =>  [
            'All Menu',                             //0
            'Create Menu',                          //1
            'Show Menu detail',                     //2
            'Update Menu',                          //3
            'Delete Menu',                          //4
            'Deleted Menu',                         //5
            'Restore Menu',                         //6
            'Permanent Delete Menu',                //7
            ],
        ],
    23 =>[   'title'=>"Patient",
        'menu' =>  [
            'All Patients',                            //0
            'Create Patient',                          //1
            'Show Patient detail',                     //2
            'Update Patient',                          //3
            'Delete Patient',                          //4
            'Deleted Patients',                        //5
            'Restore Patient',                         //6
            'Permanent Delete Patient',                //7
            'Doctor Patients',                         //8
            ],
        ],
    24 =>[   'title'=>"Dignostics",
            'menu' =>  [
                'All Dignostics',                       //0
                'Create Dignostics',                    //1
                'Show Dignostics detail',               //2
                'Update Dignostics',                    //3
                'Delete Dignostics',                    //4
                'Deleted Dignostics',                   //5
                'Restore Dignostics',                   //6
                'Permanent Delete Dignostics'           //7
            ],
        ],
    25 =>[   'title'=>"Patient Risk Factor",
            'menu' =>  [
                'All Patient Risk Factor',                       //0
                'Create Patient Risk Factor',                    //1
                'Show Patient Risk Factor detail',               //2
                'Update Patient Risk Factor',                    //3
                'Delete Patient Risk Factor',                    //4
                'Deleted Patient Risk Factor',                   //5
                'Restore Patient Risk Factor',                   //6
                'Permanent Delete Patient Risk Factor'           //7
            ],
        ],
    26 =>[  'title'=>"Appointments",
            'menu' =>  [
                'All Appointments',                       //0
                'Create Appointments',                    //1
                'Show Appointments detail',               //2
                'Update Appointments',                    //3
                'Delete Appointments',                    //4
                'Deleted Appointments',                   //5
                'Restore Appointments',                   //6
                'Permanent Delete Appointments',          //7
                'Update Appointment Status',              //8
                'Parent Appointments'                     //9
            ],
        ],
    27 =>[  'title'=>"Bank Account",
            'menu' =>  [
                'All bank account',                       //0
                'Create bank account',                    //1
                'Show bank account detail',               //2
                'Update bank account',                    //3
                'Delete bank account',                    //4
                'Deleted bank account',                   //5
                'Restore bank account',                    //6
                'Permanent Delete bank account',           //7
                'Permissions assigned to bank account',    //8
                'Dont Show bank account',                  //9
            ],
        ],
    28 =>[  'title'=>"Reviews",
            'menu' =>  [
                'All Review',                       //0
                'Create Review',                    //1
                'Show Review detail',               //2
                'Update Review',                    //3
                'Delete Review',                    //4
                'Change Review Status',             //5
            ],
        ],
    29 =>[  'title'=>"Medical record",
        'menu' =>  [
                'All Medical record',                       //0
                'Create Medical record',                    //1
                'Show Medical record detail',               //2
                'Update Medical record',                    //3
                'Delete Medical record',                    //4
                'Deleted Medical record',                   //5
                'Restore Medical record',                   //6
                'Permanent Medical record',                 //7
                'Show Medical record detail',               //8
            ],
        ],
    30 =>[  'title'=>"Prescription",
        'menu' =>  [
                'All Prescriptions',                       //0
                'Create Prescriptions',                    //1
                'Show Prescriptions detail',               //2
                'Update Prescriptions',                    //3
                'Delete Prescriptions',                    //4
                'Deleted Prescriptions',                   //5
                'Restore Prescriptions',                   //6
                'Permanent Delete Prescriptions',          //7
                'Upload Prescriptions',                    //8
                'Show Uploaded Prescriptions',             //9
            ],
        ],
    31 =>[  'title'=>"Prescription Diagnostic",
        'menu' =>  [
                'All Prescriptions Diagnostic',             //0
                'Create Prescriptions Diagnostic',          //1
                'Show Prescriptions Diagnostic detail',     //2
                'Update Prescriptions Diagnostic',          //3
                'Delete Prescriptions Diagnostic',          //4
                'All Diagnostics',                          //5
            ],
        ],
    32 =>[  'title'=>"Plan Categories",
        'menu' =>  [
                'All Plan Categories',                     //0
                'Create Plan Category',                    //1
                'Show Plan Categories detail',             //2
                'Update Plan Categories',                  //3
                'Delete Plan Categories',                  //4
                'Deleted Plan Categories',                 //5
                'Restore Plan Categories',                 //6
                'Permanent Delete Plan Categories',        //7
            ],
        ],
    33 =>[  'title'=>"Partnership",
        'menu' =>  [
                'All Partnership',                       //0
                'Create Partnership',                    //1
                'Show Partnership detail',               //2
                'Update Partnership',                    //3
                'Delete Partnership',                    //4
                'Deleted Partnership',                   //5
                'Restore Partnership',                   //6
                'Permanent Delete Partnership',          //7
                'Renew Partnership',                     //8
                'Show Uploaded Partnership',             //9
            ],
        ],
    34 =>[  'title'=>"Appointment Bank Payment",
        'menu' =>  [
                'All Appointment Payment',             //0
                'Create Appointment Payment',          //1
                'Show Appointment Payment detail',     //2
                'Update Appointment Payment',          //3
                'Appointment Payment File',            //4
            ],
        ],
    35 =>[   'title'=>"Doctor Specialty",
        'menu' =>  [
            'All Doctor Specialty',                       //0
            'Create Doctor Specialty',                    //1
            'Show Doctor Specialty detail',               //2
            'Update Doctor Specialty',                    //3
            'Delete Doctor Specialty',                    //4
            'Deleted Doctor Specialty',                   //5
            'Restore Doctor Specialty',                   //6
            'Permanent Delete Doctor Specialty',          //7
            'List of All Doctor Specialty'                //8
        ],
    ],
    36 =>[   'title'=>"Payment Summary",
        'menu' =>  [
            'All Payment Summary',                       //0
            'Create Payment Summary',                    //1
            'Show Payment Summary detail',               //2
            'Update Payment Summary',                    //3
            'Delete Payment Summary',                    //4
            'Deleted Payment Summary',                   //5
            'Restore Payment Summary',                   //6
            'Permanent Delete Payment Summary',          //7
        ],
    ],
    101 => [
            'title' => 'AUTH',
            'menu' => [
                'Login-success',
                'Login-failed',
                'profile',
                'INACTIVE-USER',
                'Logout',
                'NOT-ALLOWED-WINDOW',
                'Login-success-otp',
                'Patient use invalid url',
                'Patient doctor not active'
        ]
    ],
    102 => [
            'title' => 'OTP',
            'menu' => [
                'OTP-GENERATED-SUCCESS',
                'UNABLE',
                'USER-INACTIVE',
                'NOT-FOUND'
        ]
    ],
    103 => [
            'title' => 'FORGET-PASSWORD-EMAIL',
            'menu' => [
                'EMAIL-GENERATED-SUCCESS',
                'PASSWORD-RESET-SUCCESS',
                'UNABLE',
                'USER-INACTIVE',
                'NOT-FOUND'
        ]
    ],

    104 => [
        'title' => 'PHONE VERIFY',
        'menu' => [
            'Unverified phone number, please verify phone first',
            'Phone verification code sent',
            'Already verified',
            'Successfull verified',
            'Invalid or expired verification code',
        ]
    ],
    105 => [
        'title' => 'EMAIL VERIFY',
        'menu' => [
            'Unverified email address, please verify email',
            'Email verification code sent',
            'No email address found',
            'Already verified',
            'Successfull verified',
            'Invalid or expired verification code',
        ]
    ],
    106 => [
        'title' => 'EMRs',
        'menu' => [
            'Invalid param or no access to upload, updated or view',
            'File viewed or downloded Successfull',
            'List of all files viewed',
            'File upload Successfull',
            'Show full medical record',
            'Permanently deleted record'
        ]
    ],

    150 => [
        'title' => 'ROLE & PERMISSION',
        'menu' => [
            'MY-ROLE',
            'MY-PERMISSION',
            'USER-ROLE',
            'USER-PERMISSION'
        ]
    ],


    201 => [
            'title' => 'PATIENT-SELF',
            'menu' => [
                'SIGNUP-DOCTOR-INVALID',
                'ERROR-WITH-EXCEPTION',
                'VALIDATION-FAILED',
                'Already signed up, but not verified phone number'
        ]
    ]


];