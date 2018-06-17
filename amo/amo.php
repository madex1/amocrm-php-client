<?php

/**
 *
 * Api Amo CRM
 *
 * В названии функций:
 *
 * deal – это сделка
 * lead – это контакт
 * 
 * если будете править апи
 * будьте внимательно, в uri api amocrm leads – это сделки,
 * а contacts – это контакты
 */


class Amo
{
    private $subdomain = ''; //default
    private $user_login = ''; //default
    private $user_hash = ''; //default

    public $status_new = '14733097'; //default

    public function __construct($domain, $login, $hash) {
        $this->subdomain = $domain;
        $this->user_login = $login;
        $this->user_hash = $hash;
    }

    public function call_curl($url, $params=false) {
        $curl = curl_init($url);

        if($params) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt'); #PHP>5.3.6 __DIR__ -> __DIR__
        curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt'); #PHP>5.3.6 __DIR__ -> __DIR__
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function login()
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/auth.php?type=json";

        $user = array(
            'USER_LOGIN' => $this->user_login,
            'USER_HASH' => $this->user_hash,
        );

        $out = $this->call_curl($url, $user);
        return json_decode($out, true);
    }

    /**
     * Получение информации по аккаунту в котором произведена авторизация.
     *
     * @return mixed
     */
    public function acc_info()
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/accounts/current";

        $out = $this->call_curl($url);
        return json_decode($out, true);
    }

    public function get_users()
    {
        $ai = $this->acc_info();
        $users = array();
        foreach ($ai['response']['account']['users'] as $user) {
            //print_r($user);
            $users[$user['login']] = $user;
        }
        return $users;
    }

    public function create_deal($deal_name, $custom_fields, $responsible_user_id)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/leads/set";

        //$deal_name = 'Заявка от '.$lead_name.' на '.NISHA;

        $params = array(
            'request' => array(
                'leads' => array(
                    'add' => array(
                        array(
                            'name' => $deal_name,
                            'responsible_user_id' => $responsible_user_id,
                            'status_id' => $this->status_new,
                            'date_create' => time(),
                            'last_modified' => time(),
                            'custom_fields' => $custom_fields
                        )
                    )
                )
            )
        );

        $result = $this->call_curl($url, $params);


        $result_arr = json_decode($result, true);

        return $result_arr['response']['leads']['add'][0]['id'];

    }

    public function create_note($deal_id, $note_text, $responsible_user_id)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/notes/set";

        //$deal_name = 'Заявка от '.$lead_name.' на '.NISHA;

        $params = array(
            'request' => array(
                'notes' => array(
                    'add' => array(
                        array(
                            'element_id' => $deal_id,
                            'element_type' => 2,
                            'text' => $note_text,
                            'responsible_user_id' => $responsible_user_id
                        )
                    )
                )
            )
        );

        $result = $this->call_curl($url, $params);

        $result_arr = json_decode($result, true);
        return $result_arr['response']['notes']['add'][0]['id'];
    }

    /*
    $linked_deal = array(...) - массив сделок
    */
    public function create_lead($phone_num, $linked_deal, $custom_fields, $responsible_user_id)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/contacts/set";

        $params = array(
            'request' => array(
                'contacts' => array(
                    'add' => array(
                        array(
                            'name' => $phone_num,
                            'responsible_user_id' => $responsible_user_id,
                            'date_create' => time(),
                            'last_modified' => time(),
                            'linked_leads_id' => $linked_deal,
                            'custom_fields' => $custom_fields
                        )
                    )
                )
            )
        );

        $result = $this->call_curl($url, $params);

        $result_arr = json_decode($result, true);

        return $result_arr['response']['contacts']['add'][0]['id'];
    }

    public function create_unsorted($deal_name, $custom_fields_deal, $custom_fields_contact, $form_data, $responsible_user_id)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/api/unsorted/add/?api_key={$this->user_hash}&login={$this->user_login}";

        $params['request']['unsorted'] = array(
            'category' => 'forms',
            'add' => array(
                array(
                    'source' => $_SERVER['HTTP_HOST'],
                    'source_uid' => NULL,
                    'source_data' => array(
                        'data' => $form_data,
                        'form_id' => 1,
                        'form_type' => 1,
                        'origin' => array(
                            'ip' => $_SERVER['SERVER_ADDR'],
                            'datetime' => '',
                            'referer' => '',
                        ),
                        'date' => time(),
                        'from' => $_SERVER['HTTP_HOST'],
                        'form_name' => '',
                    ),
                    'data' => array(
                        'leads' => array(
                            array(
                                'name' => $deal_name,
                                'custom_fields' => $custom_fields_deal,
                                'date_create' => time(),
                                'responsible_user_id' => $responsible_user_id,
                                /*    'tags' => 'some-good-tag',
                                    'notes' => array( array(
                                            'text' => 'Note',
                                            'note_type' => 4,
                                            'element_type' => 2,
                                        ),
                                    ),*/
                            ),
                        ),
                        'contacts' => array(
                            array(
                                'name' => $deal_name,
                                'custom_fields' => $custom_fields_contact,
                                'date_create' => time(),
                                'responsible_user_id' => $responsible_user_id,
                                /*'tags' => 'my-super-form',
                                'notes' => array(
                                    array(
                                        'text' => '',
                                        'note_type' => 4,
                                        'element_type' => 1,
                                    ),
                                ),*/
                            ),
                        ),
                        /*  'companies' => array(),*/
                    ),
                ),
            ),
        );

        $result = $this->call_curl($url, $params);

        $result_arr = json_decode($result, true);

        return $result_arr;
    }

    public function create_task($element_id, $text, $user_id, $element_type = 2)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/tasks/set";

        $params = array(
            'request' => array(
                'tasks' => array(
                    'add' => array(
                        array(
                            "element_id" => $element_id,
                            "element_type" => $element_type,
                            "task_type" => 1,
                            "text" => $text,
                            "complete_till" => time() + 30 * 60,
                            "responsible_user_id" => $user_id
                        )
                    )
                )
            )
        );

        $result = $this->call_curl($url, $params);

        $result_arr = json_decode($result, true);

        return $result_arr['response']['contacts']['add'][0]['id'];
    }

    public function get_leads($query)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/contacts/list?query={$query}";

        $result = $this->call_curl($url);
        return $result;
    }

    /**
     * Метод позволяет получить подробную информацию о уже созданных сделках,
     * имеет возможность фильтрации данных и постраничной выборки.
     *
     * @param $params <Параметры запроса>
     * @param string $query <Параметр ?query >
     * @param string $amo <Учётная запись Amo>
     * @return mixed
     */
    public function get_deals($params, $query = 'query=')
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/leads/list?{$query}{$params}";

        $result = $this->call_curl($url);

        return $result;
    }

    public function get_leads_relations($element_id)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/contacts/links?contacts_link=" . serialize($element_id);

        $result = $this->call_curl($url);

        $result_arr = json_decode($result, true);
        return $result_arr['response']['links'];
    }

    public function get_deals_relations($element_id)
    {
        $url = "https://{$this->subdomain}.amocrm.ru/private/api/v2/json/contacts/links?deals_link={$element_id}";

        $result = $this->call_curl($url);

        $result_arr = json_decode($result, true);
        return $result_arr['response']['links'];
    }
}

