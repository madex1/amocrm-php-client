<?php
ini_set('default_charset', 'utf-8');
require_once '../amo/amo2.php';

/*
Чтобы посмотреть id полей, запустите пример accinfo.php
*/

// кастомные поля контакта
$custom_fields_contact = array();

/**
 *  Телефон
 *
 *  Enums id => Value
 *
 * [87417] => WORK
 * [87419] => WORKDD
 * [87421] => MOB
 * [87423] => FAX
 * [87425] => HOME
 * [87427] => OTHER
 *
 */

    $custom_fields_contact[] = array(
        'code' => 'PHONE',
        'id' => 38427,
        'values' => array(
            array(
                'value' => $VAR["phone"],
                'enum' => '87417', //'WORK'
            )
        )
    );

/**
 * Email
 *
 * Enums  id=>value
 * [87429] => WORK
 * [87431] => PRIV
 * [87433] => OTHER
 */

    $custom_fields_contact[] = array(
        'code' => 'EMAIL',
        'id' => 38429,
        'values' => array(
            array(
                'value' => $VAR["email"],
                'enum' => '87429' //WORK
            )
        )
    );


// кастомные поля сделки
$custom_fields_deal = array();

//Информация о заявке
$custom_fields_deal[] = array(
    'code' => '',
    'id' => 39257,
    'values' => array(
        array(
            'value' => $message
        )
    )
);

/***
 * Типо ENUM
 *
 * [89381] => Оклад
 * [89383] => Smart City
 * [89385] => City Driver
 *
 */

$custom_fields_deal[] = array(
    'code' => '',
    'id' => 39271,
    'values' => array(
        array(
            'value' => 89383
        )
    )
);


//данные формы (для неразобранных)
$form_data = array();


    $form_data['name_1'] = array(
        'type' => 'text',
        'id' => 'name',
        'element_type' => '1',
        'name' => 'Имя',
        'value' => $VAR['name'],
    );


    $form_data['phone_1'] = array(
        'type' => 'text',
        'id' => 'phone',
        'element_type' => '1',
        'name' => 'Телефон',
        'value' => $VAR['phone'],
    );


    $form_data['message'] = array(
        'type' => 'text',
        'id' => 'message',
        'element_type' => '2',
        'name' => 'Сообщение',
        'value' => $VAR['message'],
    );


    $form_data['type'] = array(
        'type' => 'text',
        'id' => 'type',
        'element_type' => '2',
        'name' => 'Форма',
        'value' => $VAR['type'],
    );


/* Добавление лида в Amo */
$amo = new Amo;
$amo->login();

$lead_name = $VAR['name'];

$res = $amo->create_unsorted($lead_name, $custom_fields_deal, $custom_fields_contact, $form_data, 1456024);


