<?php

namespace Vicoders\ContactForm;

global $wpdb;
define('PREFIX_TABLE', $wpdb->prefix);

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Exception;
use NF\Facades\App;
use NF\Facades\Request;
use Vicoders\ContactForm\Console\PublishCommand;
use Vicoders\ContactForm\Facades\Office;
use Vicoders\ContactForm\Models\Contact;
use Vicoders\ContactForm\Pages\Option;
use Vicoders\ContactForm\Paginate\PaginationHelper;

class ContactFormServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('ContactFormView', function ($app) {
            $view = new \NF\View\View;
            $view->setViewPath(__DIR__ . '/../resources/views');
            $view->setCachePath(__DIR__ . '/../resources/cache');
            return $view;
        });
        $this->app->singleton('ContactFormManager', function ($app) {
            return new Manager;
        });

        $this->app->singleton('PaginationHelper', function ($app) {
            return new PaginationHelper;
        });

        if (is_admin()) {
            $this->registerAdminMenu();
            $this->registerAdminPostAction();
        }
        $this->registerAction();
    }

    public function registerCommand()
    {
        return [
            PublishCommand::class,
        ];
    }

    public function registerAdminMenu()
    {
        $option = new Option;
        $option->register();
    }

    public function registerAdminPostAction()
    {
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_media();
        });

        add_action('admin_enqueue_scripts', function () {
            $app_css  = wp_slash(get_stylesheet_directory_uri() . '/vendor/nf/contact-form/assets/dist/app.css');
            $app_js   = wp_slash(get_stylesheet_directory_uri() . '/vendor/nf/contact-form/assets/dist/app.js');
            $admin_js = wp_slash(get_stylesheet_directory_uri() . '/vendor/nf/contact-form/assets/dist/admin.js');

            if ($this->app->app_config['is_plugin'] === true) {
                $app_css  = wp_slash(plugin_dir_url(dirname(dirname(__FILE__))) . 'contact-form/assets/dist/app.css');
                $app_js   = wp_slash(plugin_dir_url(dirname(dirname(__FILE__))) . 'contact-form/assets/dist/app.js');
                $admin_js = wp_slash(plugin_dir_url(dirname(dirname(__FILE__))) . 'contact-form/assets/dist/admin.js');
            }

            wp_enqueue_style(
                'admin-contact-style',
                $app_css,
                false
            );
            wp_enqueue_script(
                'frontend-contact-scripts',
                $app_js,
                'jquery',
                '1.0.4',
                true
            );
            wp_enqueue_script(
                'admin-contact-scripts',
                $admin_js,
                'jquery',
                '1.0.4',
                true
            );
            $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $params   = [
                'ajax_url' => admin_url('admin-ajax.php', $protocol),
            ];

            wp_localize_script('admin-contact-scripts', 'ajax_obj', $params);
        });

        add_action('wp_ajax_change_status_record_contact', [$this, 'changeStatus']);
        add_action('wp_ajax_delete_record_contact', [$this, 'deleteRecord']);
        add_action('wp_ajax_export_record_contact', [$this, 'exportRecord']);
        add_action('wp_ajax_send_bulk_email', [$this, 'sendBulkEmail']);
        add_action('wp_ajax_send_all_email', [$this, 'sendAllEmail']);
    }

    public function handle()
    {
        $data['message'] = 'An error while save data!';
        $request         = Request::except('action', 'type', 'name_slug', 'status');
        $type            = Request::only('type');
        $name_slug       = Request::only('name_slug');
        $status          = Request::only('status');
        if (!empty($request)) {
            $contact            = new Contact();
            $contact->data      = json_encode($request);
            $contact->type      = $type['type'];
            $contact->name_slug = $name_slug['name_slug'];
            $contact->status    = $status['status'];
            $result             = $contact->save();
            if ($result) {
                $data['message'] = __('Your infomation is sent', 'vicoders');
            }
        }
        wp_send_json(compact('data'));
    }

    public function registerAction()
    {
        add_action('wp_enqueue_scripts', function () {
            $app_css = wp_slash(get_stylesheet_directory_uri() . '/vendor/nf/contact-form/assets/dist/app.css');
            $app_js  = wp_slash(get_stylesheet_directory_uri() . '/vendor/nf/contact-form/assets/dist/app.js');

            if ($this->app->app_config['is_plugin'] === true) {
                $app_css = wp_slash(plugin_dir_url(dirname(dirname(__FILE__))) . 'contact-form/assets/dist/app.css');
                $app_js  = wp_slash(plugin_dir_url(dirname(dirname(__FILE__))) . 'contact-form/assets/dist/app.js');
            }

            wp_enqueue_style(
                'contact-form-style',
                $app_css,
                false
            );
            wp_enqueue_script(
                'contact-form-scripts',
                $app_js,
                'jquery',
                '1.1',
                true
            );
        });
        add_action('wp_ajax_handle_contact_form', [$this, 'handle']);
        add_action('wp_ajax_nopriv_handle_contact_form', [$this, 'handle']);

        add_shortcode('nf_contact_form', function ($args) {
            $manager = App::make('ContactFormManager');
            $forms   = $manager->getForms();
            if (!isset($forms)) {
                throw new \Exception("Please register your option scheme", 1);
            }
            $form      = $manager->getForm($args['name']);
            $type      = $form->getType();
            $style     = $form->getStyle();
            $status    = $form->getInitStatus();
            $name_slug = str_slug($form->getName());
            $fields    = $form->fields;
            return App::make('ContactFormView')->render('contact_form', compact('fields', 'type', 'style', 'name_slug', 'status'));
        });
    }

    public function changeStatus()
    {
        $data['message'] = 'Data not found !';
        $request         = Request::except('action');
        if (!empty($request)) {
            $id     = $request['id'];
            $status = $request['status'];
            if (!isset($id) || !isset($status)) {
                $data['message'] = __('ID or Status value is undefined!', 'vicoders');
                wp_send_json(compact('data'));
            }
            $contact         = Contact::find($id);
            $contact->status = $status;
            $result          = $contact->save();
            if ($result) {
                $data['message'] = __('Change status is successful', 'vicoders');
            }
        }
        wp_send_json(compact('data'));
    }

    public function deleteRecord()
    {
        $data['message'] = __('An error occur ! Delete unsuccessful', 'vicoders');
        $data['status']  = 0;
        $request         = Request::except('action');
        if (!empty($request)) {
            $id = (int) $request['id'];
            if (!isset($id)) {
                $data['message'] = __("Record doesn't exist !", 'vicoders');
                wp_send_json(compact('data'));
            }
            $contact = Contact::find($id);
            $result  = $contact->delete();
            if ($result) {
                $data['message'] = __('Delete record successful', 'vicoders');
                $data['status']  = 1;
            }
        }
        wp_send_json(compact('data'));
    }

    public function exportRecord()
    {
        $data = [
            'message' => __('An error occur ! Export failure', 'vicoders'),
            'status'  => 0,
            'path'    => '',
        ];
        $request = Request::except('action');
        if (!empty($request)) {
            $page         = $request['page'];
            $form_name    = $request['name'];
            $query        = new Contact();
            $query        = $query->where('name_slug', $form_name);
            $contact_data = $query->orderBy('id', 'DESC')->get();
            $path         = Office::export($form_name, $contact_data);
            $data         = [
                'message' => 'Export successful',
                'status'  => 1,
                'path'    => $path,
            ];
        }
        wp_send_json(compact('data'));
    }

    public function sendBulkEmail()
    {
        $data = [
            'message' => __('An error occur ! Send email failure', 'vicoders'),
            'status'  => 0,
        ];
        $request        = Request::except('action');
        $manager        = App::make('ContactFormManager');
        $page           = $request['page'];
        $form_name      = $request['name'];
        $email_template = $request['email_template'];
        $subject        = $request['subject'];
        $forms          = $manager->getForms();
        if (!isset($forms)) {
            throw new \Exception("Please register your option scheme", 1);
        }
        $form             = $manager->getForm($form_name);
        $config_email     = $form->getConfigEmail();
        $variables_email  = $form->getVariableEmail();
        $get_all_template = $form->getTemplateEmail();
        $params           = [];

        if ($config_email) {
            $user_data    = [];
            $query        = new Contact();
            $query        = $query->whereIn('id', $request['ids']);
            $contact_data = $query->orderBy('id', 'DESC')->get();
            if (!empty($contact_data)) {
                foreach ($contact_data as $key => $item) {
                    $item        = json_decode($item->data, true);
                    $user_data[] = [
                        'name' => $item[$variables_email['name']],
                        'to'   => $item[$variables_email['email']],
                        'from' => $config_email['mail_from'],
                    ];
                }

                if (!empty($get_all_template)) {
                    foreach ($get_all_template as $key => $item) {
                        if ($email_template == $item['path']) {
                            $params = $item['params'];
                            break;
                        }
                    }
                }

                $users = collect($user_data);
                $users = $users->map(function ($item) use ($params, $subject) {
                    $tmp_user = new \Vicoders\Mail\Models\User();
                    $tmp_user->setName($item['name'])
                        ->setTo($item['to'])
                        ->setFrom($item['from'])
                        ->setSubject($subject)
                        ->setParams($params);
                    return $tmp_user;
                });
                $email_template = file_get_contents($email_template);
                $email          = new \Vicoders\Mail\Email($config_email);
                $email->multi($users, $email_template);
                $data = [
                    'message' => 'Send email successful',
                    'status'  => 1,
                ];
                echo "<pre>";
                var_dump($users);
                die;
            }
            wp_send_json(compact('data'));
        }
    }

    public function sendAllEmail()
    {
        $data = [
            'message' => __('An error occur ! Send email failure', 'vicoders'),
            'status'  => 0,
        ];
        $request        = Request::except('action');
        $manager        = App::make('ContactFormManager');
        $page           = $request['page'];
        $form_name      = $request['name'];
        $email_template = $request['email_template'];
        $subject        = $request['subject'];
        $forms          = $manager->getForms();
        if (!isset($forms)) {
            throw new \Exception("Please register your option scheme", 1);
        }
        $form             = $manager->getForm($form_name);
        $config_email     = $form->getConfigEmail();
        $variables_email  = $form->getVariableEmail();
        $get_all_template = $form->getTemplateEmail();
        $params           = [];

        if (!empty($get_all_template)) {
            foreach ($get_all_template as $key => $item) {
                if ($email_template === $item['path']) {
                    $params = $item['params'];
                    break;
                }
            }
        }

        if ($config_email) {
            $user_data    = [];
            $query        = new Contact();
            $query        = $query->where('name_slug', $form_name);
            $contact_data = $query->orderBy('id', 'DESC')->get();
            if (!empty($contact_data)) {
                foreach ($contact_data as $key => $item) {
                    $item        = json_decode($item->data, true);
                    $user_data[] = [
                        'name' => $item[$variables_email['name']],
                        'to'   => $item[$variables_email['email']],
                        'from' => $config_email['mail_from'],
                    ];
                }

                $users         = collect($user_data);
                $convert_users = $users->map(function ($item) use ($params, $subject) {
                    $tmp_user = new \Vicoders\Mail\Models\User();
                    $tmp_user->setName($item['name'])
                        ->setTo($item['to'])
                        ->setFrom($item['from'])
                        ->setSubject($subject)
                        ->setParams($params);
                    return $tmp_user;
                });
                $email_template = file_get_contents($email_template);
                $email          = new \Vicoders\Mail\Email($config_email);
                $email->multi($convert_users, $email_template);
                $data = [
                    'message' => __('Sent all email successful', 'vicoders'),
                    'status'  => 1,
                ];
            }
            wp_send_json(compact('data'));
        }
    }
}
