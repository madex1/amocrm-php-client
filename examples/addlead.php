<?php
ini_set('default_charset', 'utf-8');
require_once '../amo/amo.php';

// кастомные поля сделки
$custom_fields_deal = array();


$custom_fields_deal[] = array(
                    'code'=>'',
                    'id'=>28348,
                    'values'=>array(
                        array(
                          'value'=>$message
                          )
                      )
                    );

// кастомное поле типа enum
$custom_fields_deal[] = array(
                    'code'=>'',
                    'id'=>28340,
                    'values'=>array(
                        array(
                          'value'=>66638
                          )
                      )
                    );
 
 // кастомные поля контакта
$custom_fields_contact = array();

$custom_fields_contact[]=array(
                    'code'=>'EMAIL',
                    'id'=>24970,
                    'values'=>array(
                        array(
                          'value'=>$VAR["email"],
                          'enum'=>'WORK'
                          )
                      )
                    );

$custom_fields_contact[]=array(
                    'code'=>'PHONE',
                    'id'=>24968,
                    'values'=>array(
                        array(
                          'value'=>$VAR["phone"],
                          'enum'=>'WORK'
                          )
                      )
                    );
      /* Добавление сделки в Amo */
      $amo = new Amo;
      $amo->login();

      $lead_name = "имя лида";
      $deal_id = $amo->create_deal($lead_name, $custom_fields_deal, 1456024);
      
      if($deal_id) {
        $contact_id = $amo->create_lead($lead_name, array($deal_id), $custom_fields_contact, 1456024);
      }
      


