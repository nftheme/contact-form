<?php

namespace NightFury\ContactForm\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class MailChimp
{
    private $api_key;
    private $client;
    public $api_version = '3.0';

    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'delete';

    public function __construct($api_key)
    {
        $this->api_key = $api_key;

        $dc = explode('-', env('MAILCHIMP_API_KEY'))[1];

        $this->client = new Client([
            'base_uri'           => "https://{$dc}.api.mailchimp.com/",
            RequestOptions::AUTH => ['user', $this->api_key],
        ]);
    }

    private function _request($method, $uri, $options = [])
    {
        try {
            $res = $this->client->request($method, "{$this->api_version}$uri", $options);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response             = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            // var_dump($options); die();
            // var_dump(json_decode($responseBodyAsString));die();
            throw new \Exception($responseBodyAsString, 0);

        }
        if ($res->getStatusCode() >= 400) {
            throw new Exception($res->getBody()->getContents(), null, 0);
        }
        $body = $res->getBody();
        return json_decode($body->getContents());
    }

    public function batch($operations)
    {
        return $this->_request(self::METHOD_POST, "/batches", [
            RequestOptions::JSON => compact('operations'),
        ]);
    }

    public function testConnection()
    {
        return $this->_request(self::METHOD_GET, '/');
    }

    public function getMergeFields($list_id, $params = [])
    {
        return $this->_request(self::METHOD_GET, "/lists/{$list_id}/merge-fields", [
            RequestOptions::QUERY => $params,
        ]);
    }

    public function deleteAllMergeFields($list_id)
    {
        $data = $this->getMergeFields($list_id, ['count' => 100]);
        if (isset($data->merge_fields)) {
            foreach ($data->merge_fields as $field) {
                $this->_request(self::METHOD_DELETE, "/lists/{$list_id}/merge-fields/{$field->merge_id}");
            }
        }
    }

    public function createMergeField($list_id, $field)
    {
        return $this->_request(self::METHOD_POST, "/lists/{$list_id}/merge-fields", [
            RequestOptions::JSON => $field,
        ]);
    }

    public function getLists($params = [])
    {
        return $this->_request(self::METHOD_GET, '/lists', [
            RequestOptions::QUERY => $params,
        ]);
    }

    public function getListById($list_id)
    {
        return $this->_request(self::METHOD_GET, "/lists/{$list_id}");
    }

    public function createList($params)
    {
        return $this->_request(self::METHOD_POST, '/lists', [
            RequestOptions::JSON => $params,
        ]);
    }

    public function deleteList($id)
    {
        return $this->_request(self::METHOD_DELETE, "/lists/{$id}");
    }

    public function addUser($list_id, $params)
    {
        return $this->_request(self::METHOD_POST, "/lists/{$list_id}/members", [
            RequestOptions::JSON => $params,
        ]);
    }

    public function updateUserStatus($list_id, $user)
    {
        $merge_field_data = $this->getMergeFields($list_id, ['count' => 100]);
        $fields           = collect($merge_field_data->merge_fields);
        $k                = $fields->search(function ($item) {
            return $item->name == 'status';
        });
        if ($k !== false) {
            $status_field_tag                = $fields->get($k)->tag;
            $merge_fields                    = [];
            $merge_fields[$status_field_tag] = $user->status;
            $this->_request(self::METHOD_PUT, "/lists/$list_id/members/" . md5($user->email), [
                RequestOptions::JSON => [
                    'merge_fields' => $merge_fields,
                ],
            ]);
        }
    }

    public function syncUser($list_id, $params)
    {
        return $this->_request(self::METHOD_PUT, "/lists/{$list_id}/members/" . md5($params['email_address']), [
            RequestOptions::JSON => $params,
        ]);
    }

    public function sync($user_id)
    {
        $user = App::make(UserRepository::class)->find($user_id);
        if ($user->isEmployer()) {
            $list_id = env('MAILCHIMP_EMPLOYER_LIST_ID');
            $query   = MailChimp::getEmployerQuery()->whereRaw('users.id =' . $user->id);
        } else if ($user->isemployee()) {
            $list_id = env('MAILCHIMP_EMPLOYEE_LIST_ID');
            $query   = MailChimp::getEmployerQuery()->whereRaw('users.id =' . $user->id);
        }
        $user = $query->first();
        if ($user) {
            $merge_field_data = MailChimp::getMergeFields($list_id, ['count' => 100]);
            $fields           = collect($merge_field_data->merge_fields);
            $merge_fields     = [];
            foreach (array_keys($user->toArray()) as $key) {
                $k = $fields->search(function ($item) use ($fields, $key) {
                    return $item->name == $key;
                });
                if ($k !== false && isset($user->toArray()[$key])) {
                    $merge_fields[$fields[$k]->tag] = $user->toArray()[$key];
                }
            }
            MailChimp::syncUser($list_id, [
                'email_address' => $user->email,
                'status'        => 'subscribed',
                'status_if_new' => 'subscribed',
                'merge_fields'  => $merge_fields,
            ]);
        }
    }

    public function getEmployerQuery()
    {
        return User::selectRaw(<<<EOF
employer.company_name,
employer.company_about,
(SELECT industries.name FROM industries RIGHT JOIN industry_user ON industry_user.industry_id = industries.id WHERE industry_user.user_id = users.id LIMIT 1) AS industry,
employer.contact_position,
employer.url,
DATE_FORMAT(users.created_at, '%Y-%m-%d') AS created_at,
DATE_FORMAT(users.updated_at, '%Y-%m-%d') AS updated_at,
users.email,
users.first_name,
users.last_name,
users.country,
DATE_FORMAT(users.activated, '%Y-%m-%d') AS activated,
users.status,
users.profile_completed_percent,
users.is_created_by_post_job_form,
users.enable_email_notification,
users.enable_marketing_email,
users.enable_job_email,
(SELECT COUNT(*) FROM jobs WHERE jobs.employer_id = users.id AND jobs.draft = 0) AS nb_job_posted,
(SELECT DATE_FORMAT(completed_date, '%Y-%m-%d') AS completed_date FROM shifts RIGHT JOIN jobs ON shifts.job_id = jobs.id WHERE jobs.employer_id = users.id AND shifts.complete = 1 ORDER BY shifts.completed_date DESC LIMIT 1) AS last_job_completed,
(SELECT DATE_FORMAT(start_time, '%Y-%m-%d') AS start_time FROM shifts RIGHT JOIN jobs ON shifts.job_id = jobs.id WHERE jobs.employer_id = users.id ORDER BY shifts.created_at DESC LIMIT 1) AS last_job_posted
EOF
        )
            ->join('employer_user','employer_user.user_id','=','users.id')
            ->join('employer','employer.id','=','employer_user.employer_id')
            ->whereHas('roles',function($q){
                $q->where('slug','employer');
            });
    }
    public function getEmployeeQuery()
    {
        return User::selectRaw(<<<EOF
users.email,
users.first_name,
users.last_name,
DATE_FORMAT(users.activated, '%Y-%m-%d') AS activated,
users.gender,
users.gender,
users.no_of_job_canceled,
users.no_of_job_applied,
users.no_of_job_rejected,
users.no_of_job_completed,
users.no_of_job_accepted,
users.status,
users.profile_completed_percent,
users.is_updated_profile,
users.profile_updated_count,
users.enable_email_notification,
users.enable_marketing_email,
users.enable_job_email,
employee.allow_to_work_in_sg,
users.contact_name,
users.birth,
DATE_FORMAT(users.created_at, '%Y-%m-%d') AS created_at,
DATE_FORMAT(users.updated_at, '%Y-%m-%d') AS updated_at,
(SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at FROM shift_user WHERE shift_user.user_id = users.id ORDER BY shift_user.created_at DESC LIMIT 1) AS date_last_applied_application,
(SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at FROM shift_user WHERE shift_user.user_id = users.id AND shift_user.accept = 1 ORDER BY shift_user.created_at DESC LIMIT 1) AS date_last_hired_application
EOF
        )
            ->join('employee','employee.user_id','=','users.id')
            ->whereHas('roles',function($q){
                $q->where('slug','employee');
            });
    }

    public function importEmployer($list_id, $max = 0)
    {
        $query = $this->getEmployerQuery();
        if ($max > 0) {
            $total    = $max;
            $per_page = $max;
        } else {
            $total    = $query->count();
            $per_page = 500;
        }
        $total_page = ceil($total / $per_page);

        $merge_field_data = $this->getMergeFields($list_id, ['count' => 100]);
        $fields           = collect($merge_field_data->merge_fields);

        $operations = [];
        for ($page = 1; $page <= $total_page; $page++) {
            $users = $query->skip(($page - 1) * $per_page)->take($per_page)->get();

            foreach ($users as $user) {
                $merge_fields = [];
                foreach (array_keys($user->toArray()) as $key) {
                    $k = $fields->search(function ($item) use ($fields, $key) {
                        return $item->name == $key;
                    });
                    if ($k !== false && $user->toArray()[$key]) {
                        $merge_fields[$fields[$k]->tag] = $user->toArray()[$key];
                    }
                }
                array_push($operations, [
                    'method' => 'PUT',
                    'path'   => "/lists/{$list_id}/members/" . md5($user->email),
                    'body'   => json_encode([
                        'email_address' => $user->email,
                        'status'        => 'subscribed',
                        'status_if_new' => 'subscribed',
                        'merge_fields'  => $merge_fields,
                    ]),
                ]);
            }
            $this->batch($operations);
        }
    }

    public function importEmployee($list_id, $max = 0)
    {
        $query = $this->getEmployeeQuery();
        if ($max > 0) {
            $total    = $max;
            $per_page = $max;
        } else {
            $total    = $query->count();
            $per_page = 500;
        }
        $total_page = ceil($total / $per_page);

        $merge_field_data = $this->getMergeFields($list_id, ['count' => 100]);
        $fields           = collect($merge_field_data->merge_fields);

        $operations = [];
        for ($page = 1; $page <= $total_page; $page++) {
            $users = $query->skip(($page - 1) * $per_page)->take($per_page)->get();

            foreach ($users as $user) {
                $merge_fields = [];
                foreach (array_keys($user->toArray()) as $key) {
                    $k = $fields->search(function ($item) use ($fields, $key) {
                        return $item->name == $key;
                    });
                    if ($k !== false && $user->toArray()[$key]) {
                        $merge_fields[$fields[$k]->tag] = $user->toArray()[$key];
                    }
                }
                array_push($operations, [
                    'method' => 'PUT',
                    'path'   => "/lists/{$list_id}/members/" . md5($user->email),
                    'body'   => json_encode([
                        'email_address' => $user->email,
                        'status'        => 'subscribed',
                        'status_if_new' => 'subscribed',
                        'merge_fields'  => $merge_fields,
                    ]),
                ]);
            }
            $this->batch($operations);
        }
    }

    public function getCampaigns()
    {
        return $this->_request(self::METHOD_GET, '/campaigns');
    }
}
