<?php

namespace Vicoders\ContactForm;

use Vicoders\ContactForm\Console\PublishCommand;
use Vicoders\ContactForm\Facades\ContactFormManager;
use Vicoders\ContactForm\Models\Contact;
use Vicoders\ContactForm\Pages\Option;
use Vicoders\ContactForm\Paginate\PaginationHelper;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Exception;
use NF\Facades\App;
use NF\Facades\Log;
use NF\Facades\Request;

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
        $this->initDirectoriesAndFiles();

        if (is_admin()) {
            $this->registerAdminMenu();
            $this->registerAdminPostAction();
        }
        $this->registerAction();
    }

    public function initDirectoriesAndFiles()
    {
        if (!file_exists(get_stylesheet_directory() . '/database/migrations/2018_01_01_000000_create_contact_table.php')) {
            copy(get_stylesheet_directory() . '/vendor/vicoders/contact-form-for-nftheme/src/database/migrations/2018_01_01_000000_create_contact_table.php', get_stylesheet_directory() . '/database/migrations/2018_01_01_000000_create_contact_table.php');
        }
        if (!is_dir(get_stylesheet_directory() . '/resources/views')) {
            throw new Exception("views folder not found", 1);
        }
        if (!is_dir(get_stylesheet_directory() . '/resources/views/vendor')) {
            mkdir(get_stylesheet_directory() . '/resources/views/vendor', 0755);
        }
        if (!is_dir(get_stylesheet_directory() . '/resources/views/vendor/option')) {
            mkdir(get_stylesheet_directory() . '/resources/views/vendor/option', 0755);
        }
        if (!file_exists(get_stylesheet_directory() . '/resources/views/vendor/option/admin.blade.php')) {
            copy(get_stylesheet_directory() . '/vendor/vicoders/contact-form-for-nftheme/resources/views/admin.blade.php', get_stylesheet_directory() . '/resources/views/vendor/option/admin.blade.php');
        }

        if (!is_dir(get_stylesheet_directory() . '/resources/views/vendor/option/pagination')) {
            mkdir(get_stylesheet_directory() . '/resources/views/vendor/option/pagination', 0755);
        }
        if (!file_exists(get_stylesheet_directory() . '/resources/views/vendor/option/pagination/default.blade.php')) {
            copy(get_stylesheet_directory() . '/vendor/vicoders/contact-form-for-nftheme/resources/views/pagination/default.blade.php', get_stylesheet_directory() . '/resources/views/vendor/option/pagination/default.blade.php');
        }
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
            wp_enqueue_style(
                'admin-contact-style',
                wp_slash(get_stylesheet_directory_uri() . '/vendor/vicoders/contact-form-for-nftheme/assets/dist/app.css'),
                false
            );
            wp_enqueue_script(
                'admin-contact-scripts',
                wp_slash(get_stylesheet_directory_uri() . '/vendor/vicoders/contact-form-for-nftheme/assets/dist/app.js'),
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

        add_action('admin_post_nto_save', [ContactFormManager::class, 'save']);
        add_action('wp_ajax_nto_remove', [ContactFormManager::class, 'remove']);
        add_action('wp_ajax_change_status_record_contact', [$this, 'changeStatus']);
        add_action('wp_ajax_delete_record_contact', [$this, 'deleteRecord']);
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
                $data['message'] = 'Your infomation is sent';
            }
        }
        wp_send_json(compact('data'));
    }

    public function registerAction()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style(
                'contact-form-style',
                wp_slash(get_stylesheet_directory_uri() . '/vendor/vicoders/contact-form-for-nftheme/assets/dist/app.css'),
                false
            );
            wp_enqueue_script(
                'contact-form-scripts',
                wp_slash(get_stylesheet_directory_uri() . '/vendor/vicoders/contact-form-for-nftheme/assets/dist/app.js'),
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
            Log::info($status);
            if (!isset($id) || !isset($status)) {
                $data['message'] = 'ID or Status value is undefined!';
                wp_send_json(compact('data'));
            }
            $contact         = Contact::find($id);
            $contact->status = $status;
            $result          = $contact->save();
            if ($result) {
                $data['message'] = 'Change status is successful';
            }
        }
        wp_send_json(compact('data'));
    }

    public function deleteRecord()
    {
        $data['message'] = 'An error occur ! Delete unsuccessful';
        $data['status']  = 0;
        $request         = Request::except('action');
        if (!empty($request)) {
            $id = (int) $request['id'];
            if (!isset($id)) {
                $data['message'] = "Record doesn't exist !";
                wp_send_json(compact('data'));
            }
            $contact = Contact::find($id);
            $result  = $contact->delete();
            if ($result) {
                $data['message'] = 'Delete record successful';
                $data['status']  = 1;
            }
        }
        wp_send_json(compact('data'));
    }
}
