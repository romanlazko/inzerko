<?php

return [
    'profile' => 'Profile',
    'password' => 'Password',
    'save' => 'Save',
    'saved' => 'Saved',
    'logout' => 'Logout',
    'fill_your_profile' => 'Fill your profile',
    'are_you_sure' => 'Are you sure?',

    'update_passport_form' => [
        'title' =>'Update Password',
        'description' => 'Ensure your account is using a long, random password to stay secure.',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'new_password_confirmation' => 'Confirm Password',
    ],

    'update_profile_information_form' => [
        'title' => 'Update Profile Information',
        'description' => 'Update your account\'s profile information.',
        'name' => 'Name',
        'email' => 'Email',
    ],

    'update_communication_information_form' => [
        'title' => 'Communication information',
        'description' => 'Other users will be able to contact you using these contacts.',
        'add_contact' => 'Add contact',
        'empty_state_heading' => 'No contact has been added',
    ],

    'update_languages_information_form' => [
        'title' => 'Select languages you speak',
        'description' => 'Other users will know in which language to address you',
        'english' => 'English',
        'russian' => 'Russian',
        'czech' => 'Czech',
    ],

    'update_telegram_information_form' => [
        'title' => 'Connect Telegram',
        'description' => 'Connect your Telegram account to get notified when you receive new messages.',
        'connect_telegram' => 'Connect Telegram',
        'disconnect_telegram' => 'Disconnect Telegram',
        'username_required' => 'You must set a Telegram @username.',
    ],

    'verify_email' => [
        'title' => 'A new verification link has been sent to the email address you provided during registration.',
        'description' => 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.',
        'resend' => 'Resend Verification Email',
    ],

    'fill_profile' => [
        'description' => 'Fill your profile information to get started.',
    ], 

    'new_message_notifications_form' => [
        'title' => 'New Message Notifications',
        'description' => 'Choose how you would like to be notified when you receive new messages.',
        'email' => 'Receive notifications of new messages by Email',
        'telegram' => 'Receive notifications of new messages by Telegram',
    ],

    'delete_user_form' => [
        'title' => 'Delete Account',
        'description' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.',
        'delete_account' => 'Delete Account',
        'cancel' => 'Cancel',
        'deleted' => 'Your account has been deleted.',
        'modal_title' => 'Are you sure you want to delete your account?',
        'modal_description' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
    ],

    'penalty' => [
        'banned' => 'Your account has been banned',
        'reason' => 'Reason',
        'reasons' => [
            'spam' => 'Spam',
            'bot' => 'Bot',
            'abuse' => 'Abuse',
            'fraud' => 'Fraud',
            'scam' => 'Scam',
            'illegal' => 'Illegal',
            'other' => 'Other',
        ],
        'expired' => 'Expired',
    ],
];