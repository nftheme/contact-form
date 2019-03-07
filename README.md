# Contact Form

- [Contact Form](#contact-form)
  - [Install Migrate and Form package.](#install-migrate-and-form-package)
  - [Installation](#installation)
    - [Install Through Composer](#install-through-composer)
    - [Add the Service Provider](#add-the-service-provider)
    - [Run migrate command](#run-migrate-command)
    - [Register your option scheme](#register-your-option-scheme)
    - [Add shortcode](#add-shortcode)
    - [Add the shortcode wherever you need](#add-the-shortcode-wherever-you-need)
    - [Custom layout for paginator](#custom-layout-for-paginator)



## Install Migrate and Form package.

> Install Migrate and Form package is require 

Refer to the instructions here: 

- [Migrate](https://github.com/nf-theme/contact-form)
- [Form](https://github.com/nf-theme/form)

## Installation

### Install Through Composer

```
composer require nf/contact-form
```

### Add the Service Provider

Open `config/app.php` and register the required service provider.

```php
  'providers'  => [
        // .... Others providers 
        \Vicoders\ContactForm\ContactFormServiceProvider::class
    ],
```
### Run migrate command

Run commands below to create needed file and migrate database

```php
// publish needed file
php command contact:publish
```

```php
// migrate database
php command migrate
```

### Register your option scheme

> You can add your option scheme to `functions.php`

All supported type can be found here 

- [Input](https://github.com/nf-theme/contact-form/blob/master/src/Abstracts/Input.php)
- [Type](https://github.com/nf-theme/contact-form/blob/master/src/Abstracts/Type.php)

```php
use Vicoders\ContactForm\Abstracts\Input;
use Vicoders\ContactForm\Abstracts\Type;
use Vicoders\ContactForm\Facades\ContactFormManager;

ContactFormManager::add([
    'name'   => 'Contact',
    'type'   => Type::CONTACT,
    'style'  => 'form-1',
    'email_enable' => true, /* default - false */
    'email_variables' => [
        'name'  => 'NAME_FIELD',
        'email' => 'EMAIL_FIELD'
    ],
    'email_config' => [
        'domain_api'      => 'http://sendmail.vicoders.com/',
        'mail_host'       => 'HOST MAIL',
        'mail_port'       => 'PORT',
        'mail_from'       => 'EMAIL_FROM',
        'mail_name'       => 'YOUR NAME',
        'mail_username'   => 'EMAIL SEND',
        'mail_password'   => 'EMAIL PASSWORD',
        'mail_encryption' => 'tls',
    ],
    'email_template' => [
        [
            'name' => 'Template 1',
            'path' => 'PATH_TO_HTML_TEMPLATE',
            'params' => [
                'name_author' => 'Garung 123',
                'post_title'  => 'this is title 123',
                'content'     => 'this is content 123',
                'link'        => 'http://google.com',
                'site_url'    => site_url(),
            ]
        ],
        [
            'name' => 'Template 2',
            'path' => 'PATH_TO_HTML_TEMPLATE',
            'params' => [
                'example_variable' => 'demo'
            ]
        ]
    ],
    'status' => [
        [
            'id' => 1,
            'name' => 'pending',
            'is_default' => true
        ],
        [
            'id' => 2,
            'name' => 'confirmed',
            'is_default' => false
        ],
        [
            'id' => 3,
            'name' => 'cancel',
            'is_default' => false
        ],
        [
            'id' => 4,
            'name' => 'complete',
            'is_default' => false
        ]
    ],
    'fields' => [
        [
            'label'      => 'Text',
            'name'       => 'firstname', // the key of option
            'type'       => Input::TEXT,
            'attributes' => [
                'required'   => true,
                'class'       => 'col-sm-6 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'      => 'Text',
            'name'       => 'lastname', // the key of option
            'type'       => Input::TEXT,
            'attributes' => [
                'required'   => true,
                'class'       => 'col-sm-6 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'      => 'Email',
            'name'       => 'email', // the key of option
            'type'       => Input::EMAIL,
            'attributes' => [
                'required'   => true,
                'class'       => 'col-sm-12 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'      => 'Phone',
            'name'       => 'phone',
            'type'       => Input::TEXT,
            'attributes' => [
                'required'   => true,
                'class'       => 'col-sm-12 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'             => 'Choose Size',
            'name'              => 'choose-size',
            'type'              => Input::SELECT,
            'list'              => [
                '0'       => '--- option ---',
                'size-l'  => 'Size L',
                'size-x'  => 'Size X',
                'size-xl' => 'Size XL',
            ],
            'selected'          => 'size-xl',
            'selectAttributes'  => [
                'class' => 'col-sm-12 form-control',
            ],
            'optionsAttributes' => [
                'placeholder' => 'Please fill field',
                'required'          => true,
            ],
        ],
        [
            'label'      => 'Textarea',
            'name'       => 'textarea',
            'type'       => Input::TEXTAREA,
            'attributes' => [
                'required'          => true,
                'class'       => 'col-sm-12 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'name'       => 'date',
            'type'       => Input::DATE,
            'attributes' => [
                'required'   => 'true',
                'class'       => 'col-sm-12 form-control email-inp-wrap',
                'placeholder' => __('Hãy nhập email của bạn', 'contactmodule'),
            ],
        ],
        [
            'value'       => 'Submit',
            'type'       => Input::SUBMIT,
            'attributes' => [
                'class'       => 'btn btn-primary btn-submit',
                'placeholder' => __('Hãy nhập email của bạn', 'contactmodule'),
            ],
        ],
    ],
]);
```

> Notice: If `selected` attribute of Input::SELECT don't set, it'll automation check title of current page with items in list which you set at `list` attribute.

### Add shortcode
> Automatic create a shortcode name `nf_contact_form` with a attribute `name` is require:

```php
[nf_contact_form name="{form_name}"]
```

Example:
```
[nf_contact_form name="Contact"]
```

### Add the shortcode wherever you need

```php
do_shortcode("[nf_contact_form name='Contact']")
```

### Custom layout for paginator

> You can change layout for pagination in `resources/views/vendor/option/pagination/default.blade.php`