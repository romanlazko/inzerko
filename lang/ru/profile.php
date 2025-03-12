<?php

return [
    'profile' => 'Профиль',
    'password' => 'Пароль',
    'save' => 'Сохранить',
    'saved' => 'Сохранено',
    'logout' => 'Выйти',
    'fill_your_profile' => 'Заполните свой профиль',
    'are_you_sure' => 'Вы уверены?',

    'update_passport_form' => [
        'title' =>'Обновить пароль',
        'description' => 'Убедитесь, что ваш аккаунт использует длинный, случайный пароль для повышения безопасности.',
        'current_password' => 'Текущий пароль',
        'new_password' => 'Новый пароль',
        'new_password_confirmation' => 'Подтвердите пароль',
    ],

    'update_profile_information_form' => [
        'title' => 'Обновить информацию о профиле',
        'description' => 'Обновите информацию о вашем профиле.',
        'name' => 'Имя',
        'email' => 'Электронная почта',
    ],

    'update_communication_information_form' => [
        'title' => 'Коммуникация с пользователями',
        'description' => 'Другие пользователи смогут связаться с вами используя эти контакты.',
        'add_contact' => 'Добавить контакт',
        'empty_state_heading' => 'Ни один из контактов не был добавлен',
    ],

    'update_languages_information_form' => [
        'title' => 'Выберите языки на которых вы говорите',
        'description' => 'Другие пользователи будут знать на каком языке к вам обращаться.',
        'english' => 'Английский',
        'russian' => 'Русский',
        'czech' => 'Чешский',
    ],

    'update_telegram_information_form' => [
        'title' => 'Подключение Telegram',
        'description' => 'Подключите Telegram для получения уведомлений о новых сообщениях.',
        'connect_telegram' => 'Подключить Telegram',
        'disconnect_telegram' => 'Отключить Telegram',
    ],

    'new_message_notifications_form' => [
        'title' => 'Уведомления о новых сообщениях',
        'description' => 'Выберите, как вы хотите получать уведомления о новых сообщениях.',
        'email' => 'Получать уведомления по электронной почте',
        'telegram' => 'Получать уведомления в Telegram',
    ],

    'verify_email' => [
        'title' => 'На указанный вами при регистрации адрес электронной почты была отправлена новая ссылка для подтверждения.',
        'description' => 'Спасибо за регистрацию! Прежде чем начать, подтвердите, пожалуйста, свой адрес электронной почты, кликнув по ссылке, которую мы только что отправили вам на почту. Если вы не получили письмо, мы с радостью отправим его снова.',
        'resend' => 'Отправить письмо повторно',
    ],

    'fill_profile' => [
        'description' => 'Заполните свой профиль, чтобы начать работу',
    ],

    'delete_user_form' => [
        'title' => 'Удалить аккаунт',
        'description' => 'После удаления вашей учетной записи все ее ресурсы и данные будут удалены безвозвратно. Перед удалением учетной записи загрузите все данные и информацию, которые вы хотите сохранить.',
        'delete_account' => 'Удалить аккаунт',
        'cancel' => 'Отмена',
        'deleted' => 'Ваш аккаунт был удален.',
        'modal_title' => 'Вы уверены, что хотите удалить своий аккаунт?',
        'modal_description' => 'После удаления вашей учетной записи все ее ресурсы и данные будут безвозвратно удалены. Пожалуйста, введите свой пароль, чтобы подтвердить, что вы хотите удалить свою учетную запись.',
    ],

    'penalty' => [
        'banned' => 'Ваш аккаунт был заблокирован',
        'reason' => 'Причина',
        'reasons' => [
            'spam' => 'Спам',
            'bot' => 'Бот',
            'abuse' => 'Нарушение правил',
            'fraud' => 'Мошенничество',
            'scam' => 'Скам',
            'illegal' => 'Нарушение закона',
            'other' => 'Другое',
        ],
        'expired' => 'Истекает',
    ],
];