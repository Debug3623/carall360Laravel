<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'success' => 'Login successfull.',
    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'inactive' => 'User is not active.',
    'deleted' => 'User is marked as deleted',
    'logout'   => 'User logout successfully',
    'not_authorized' => 'You are not allowed to perform this action (all or some records)',
    'source_not_set' => 'Login source not passed',
    'invalid_source' => 'Invalid login source request',
    'notallowed'    => 'Not allowed to login at current panel',
    'unverified_phone' => 'Phone number not verified',
    'unverified_email' => 'Given email address not verified',
    'already_verified_phone' => 'Phone number already in verified state',
    'already_verified_email' => 'Email address already in verified state',
    'phone_code_generated' => 'Phone verification code generated and sent :code',
    'email_code_generated' => 'Email address verification code generated and sent',

    'success_verified_phone' => 'Phone number successfully verified',
    'email_not_available' => 'Email address not avialable for verification',
    'success_verified_email' => 'Email address successfully verified',

    'doctor_inactive' => 'Associated doctor not active',
    'doctor_phone_used' => 'Patient not allowed to use doctor phone number',

    'old_password_not_match'=> 'old password doesnt matched',
    'old_password_can_not_be_new_password'=> 'New password can not be the old password!',


    'forget.email_error'            => 'Email address not found',
    'forget.user_inactive'          => 'User Inactive',
    'forget.unable'                 => 'Unable to generate verification code',
    'forget.invalid'                => 'Invalid method for forget password',
    'forget.fail'                   => 'Mail sending failed',
    'forget.code_sent'              => 'Reset code sent via email',
    'forget.success'                => 'Successfully sent verification code',
    'forget.invalid_code'           => 'Code expired or invalid',
    'forget.expired_code'           => 'Code expired',
    'forget.used_code'              => 'Already used code to reset password',
    'forget.password_reset_success' => 'Password reset successfully, please check your email for new password',
    'forget.password_fail'          => 'New Password generation failed. Please try again.',

    'forget.patient.url'            => 'Invalid forget password doctor URL',



    'otp.not_found'                 => 'Given phone number not found',
    'otp.expired_code'              => 'Code expired/invalid or already in used',
    'otp.not_active'                => 'User is not active',
    'otp.not_verified'              => 'Phone number is not verified for OTP',
    'otp.unable'                    => 'Unable to generate OTP request',
    'otp.success'                   => 'OTP code generated successfully. Please login by using OTP as password :code',
    
    'patient.invalid_url'           => 'Invalid login details or url',
    'patient.inactive_doctor'       => 'Associated doctor not active, please contact doctor support',

];
