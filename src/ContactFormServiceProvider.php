<?php

namespace Garung\ContactForm;

use Garung\ContactForm\Console\PublishCommand;
use Garung\ContactForm\Facades\ContactFormManager;
use Garung\ContactForm\Models\Contact;
use Garung\ContactForm\Pages\Option;
use Garung\ContactForm\Paginate\PaginationHelper;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Exception;
use NF\Facades\App;
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

    public function initDirectoriesAndFiles() {
        if (!file_exists(get_stylesheet_directory() . '/database/migrations/2018_01_01_000000_create_contact_table.php')) {
            copy(get_stylesheet_directory() . '/vendor/garung/contact-form-for-nftheme/src/database/migrations/2018_01_01_000000_create_contact_table.php', get_stylesheet_directory() . '/database/migrations/2018_01_01_000000_create_contact_table.php');
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
            copy(get_stylesheet_directory() . '/vendor/garung/contact-form-for-nftheme/resources/views/admin.blade.php', get_stylesheet_directory() . '/resources/views/vendor/option/admin.blade.php');
        }

        if (!is_dir(get_stylesheet_directory() . '/resources/views/vendor/option/pagination')) {
            mkdir(get_stylesheet_directory() . '/resources/views/vendor/option/pagination', 0755);
        }
        if (!file_exists(get_stylesheet_directory() . '/resources/views/vendor/option/pagination/default.blade.php')) {
            copy(get_stylesheet_directory() . '/vendor/garung/contact-form-for-nftheme/resources/views/pagination/default.blade.php', get_stylesheet_directory() . '/resources/views/vendor/option/pagination/default.blade.php');
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
                wp_slash(get_stylesheet_directory_uri() . '/vendor/garung/contact-form-for-nftheme/assets/dist/app.css'),
                false
            );
            wp_enqueue_script(
                'admin-contact-scripts',
                wp_slash(get_stylesheet_directory_uri() . '/vendor/garung/contact-form-for-nftheme/assets/dist/app.js'),
                'jquery',
                '1.0',
                true
            );
        });
        add_action('admin_post_nto_save', [ContactFormManager::class, 'save']);
        add_action('wp_ajax_nto_remove', [ContactFormManager::class, 'remove']);
    }

    public function handle()
    {
        $data['message'] = 'An error while save infomation !';
        $request         = Request::except('action', 'type');
        $type       = Request::only('type');
        if (!empty($request)) {
            $contact               = new Contact();
            $contact->data         = json_encode($request);
            $contact->type_of_name = $type['type'];
            $contact->status       = Contact::DEACTIVE;
            $result                = $contact->save();
            if ($result) {
                $data['message'] = 'Your email is saved successfully';
            }
        }
        wp_send_json(compact('data'));
    }

    public function registerAction()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style(
                'contact-form-style',
                wp_slash(get_stylesheet_directory_uri() . '/vendor/garung/contact-form-for-nftheme/assets/dist/app.css'),
                false
            );
            wp_enqueue_script(
                'contact-form-scripts',
                wp_slash(get_stylesheet_directory_uri() . '/vendor/garung/contact-form-for-nftheme/assets/dist/app.js'),
                'jquery',
                '1.0',
                true
            );
        });
        add_action('wp_ajax_handle_contact_form', [$this, 'handle']);

        add_shortcode('nf_contact_form', function ($args) {

            $manager = App::make('ContactFormManager');
            $forms   = $manager->getForms();
            if (!isset($forms)) {
                throw new \Exception("Please register your option scheme", 1);
            }
            $form    = $manager->getForm($args['name']);
            $type = $form->getType();
            $fields = $form->fields;
            return App::make('ContactFormView')->render('contact_form', compact('fields', 'type'));
        });
    }
}
