## Create contact and subcribe form easy with ContactFormModule 
 > It's an extension for our theme https://github.com/hieu-pv/nf-theme 
 
### A. Before Install
##### Install Migrate and Form package.
- <strong style="color: red; font-size: 16px;">Install Migrate and Form package is require !</strong>

Refer to the instructions here: 
- [Migrate](https://github.com/garungabc/MigrateForNFTheme)
- [Form](https://github.com/hieu-pv/nf-form)

### B. Installation
##### Step 1: Install Through Composer
```
composer require vicoders/contact-form-for-nftheme
```
##### Step 2: Add the Service Provider
> Open `config/app.php` and register the required service provider.

```php
  'providers'  => [
        // .... Others providers 
        \Vicoders\ContactForm\ContactFormServiceProvider::class
    ],
```
##### Step 3: Run migrate command

- Run 2 commands below to render file and run migrate:
```php
php command contact:publish

php command migrate
```

##### Step 4: Register your option scheme
> You can add your option scheme to `functions.php`

All supported type can be found here 
- [Input](https://github.com/garungabc/ContactFormForNfTheme/blob/master/src/Abstracts/Input.php)
- [Type](https://github.com/garungabc/ContactFormForNfTheme/blob/master/src/Abstracts/Type.php)

```php
use Vicoders\ContactForm\Abstracts\Input;
use Vicoders\ContactForm\Abstracts\Type;
use Vicoders\ContactForm\Facades\ContactFormManager;

ContactFormManager::add([
    'name'   => 'Contact',
    'type'   => Type::CONTACT,
    'style'  => 'form-1',
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

Notice: If `selected` attribute of Input::SELECT don't set, it'll automation check title of current page with items in list which you set at `list` attribute.

##### Step 5: Add shortcode
> Automatic create a shortcode name `nf_contact_form` with a attribute `name` is require:

```php
[nf_contact_form name="{form_name}"]
```

Example:
```
[nf_contact_form name="Contact"]
```

##### Step 6: Insert shortcode wherever you need
> Very easy
```php
do_shortcode("[nf_contact_form name='Contact']")
```

## Custom layout for paginator
> You can change layout for pagination in `resources/views/vendor/option/pagination/default.blade.php`

## Last step
> {tip} Drink tea and relax !