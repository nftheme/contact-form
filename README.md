## Create contact and subcribe form easy with ContactFormModule 
 > It's an extension for our theme https://github.com/hieu-pv/nf-theme 
 
#### Installation
##### Step 1: Install Through Composer
```
composer require garung/contact-form-for-nftheme
```
##### Step 2: Add the Service Provider
> Open `config/app.php` and register the required service provider.

```php
  'providers'  => [
        // .... Others providers 
        \Garung\ContactForm\ContactFormServiceProvider::class
    ],
```
##### Step 3: Register your option scheme
> You can add your option scheme to `functions.php`

All supported type can be found here 
- [https://github.com/garungabc/ContactFormForNfTheme/blob/master/src/Abstracts/Input.php](https://github.com/garungabc/ContactFormForNfTheme/blob/master/src/Abstracts/Input.php)
- [https://github.com/garungabc/ContactFormForNfTheme/blob/master/src/Abstracts/Type.php](https://github.com/garungabc/ContactFormForNfTheme/blob/master/src/Abstracts/Type.php)

```
use Garung\ContactForm\Abstracts\Input;
use Garung\ContactForm\Abstracts\Type;
use Garung\ContactForm\Facades\ContactFormManager;

ContactFormManager::add([
    'name'   => 'subcribe', // or 'contact'
    'type'   => Type::CONTACT,
    'fields' => [
        [
            'label'      => 'Text',
            'name'       => 'firstname', // the key of option
            'type'       => Input::TEXT,
            'required'   => true,
            'attributes' => [
                'class'       => 'col-sm-6 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'      => 'Text',
            'name'       => 'lastname', // the key of option
            'type'       => Input::TEXT,
            'required'   => true,
            'attributes' => [
                'class'       => 'col-sm-6 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'      => 'Email',
            'name'       => 'email', // the key of option
            'type'       => Input::EMAIL,
            'required'   => true,
            'attributes' => [
                'class'       => 'col-sm-12 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'      => 'Phone',
            'name'       => 'phone',
            'type'       => Input::TEXT,
            'required'   => true,
            'attributes' => [
                'class'       => 'col-sm-12 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
        [
            'label'             => 'Choose Size',
            'name'              => 'choose-size',
            'type'              => Input::SELECT,
            'required'          => true,
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
            ],
        ],
        [
            'label'      => 'Textarea',
            'name'       => 'textarea',
            'type'       => Input::TEXTAREA,
            'required'   => true,
            'attributes' => [
                'class'       => 'col-sm-12 form-control',
                'placeholder' => 'Please fill field',
            ],
        ],
    ],
]);
```

##### Step 4: Add shortcode
> Automatic create a shortcode name `nf_contact_form` with a attribute `name` is require:

```php
[nf_contact_form name="{form_name}"]
```

Example:
```
[nf_contact_form name="Contact"]
```

##### Step 5: Insert shortcode wherever you need
> Very easy
```php
do_shortcode("[nf_contact_form name='Contact']")
```

## Custom layout for paginator
> You can change layout for pagination in `resources/views/vendor/option/pagination/default.blade.php`

## Last step
> {tip} Drink tea and relax !