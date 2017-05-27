# silverstripe-thankfully

If you're tired of having to continuously create dozens of different "Thank You" pages than _thankfully_ this module exists!

With this module you can easily create a "Thank You" page that is configurable in the CMS for any page type that you assign this module to.

## Features
- Page creates itself under the parent on dev/build
- Query string support for conversion tracking etc, configurable from the CMS

## Installation

Installation is supported via composer only:

```sh
$ composer require zanderwar/silverstripe-thankfully ~2.0
```

Run a dev/build afterwards

## Configuration

1. Open your `mysite/_config.php` file
2. Add:
    ```php
    ContactPage::add_extension('ThankfullyExtension');
    ContactPage::add_extension('ThankfullyControllerExtension');
    ```
3. Run a `?flush=1`
4. Open the page that has `ContactPage` (in this example) as the type within the CMS and you will now see a "Thank You" tab

In your controller (in this example `ContactPage_Controller`) you now have access to the method `$this->getThankYouPage()` which you can then immediately `return $this->redirect($this->getThankYouPage()->Link())`. This would most commonly be done in a form processing method

If the page title and/or content is not set in the page with the extension, it will fallback to the values set in the `Thank You (Generic)` that the dev/build created in the root of your site tree, if no defaults are configured, then the visitor will get a bit of an ugly experience

## Example

```php
class ExamplePage_Controller extends Page_Controller {

    private static $allowed_actions = array(
        'index'
    );

    public function index()
    {
        /** @var ThankfullyPage $thankYou */
        $thankYou = $this->getThankYouPage();
        $thankYou->setAllowed(true); // Required if "Always Allowed" is disabled in the CMS
        $thankYou->setReturnTo('/home/'); // Optional, if not provided the link to the parent page will be used

        return $this->redirect($thankYou->Link());
    }
}
```

## Contributing
If you would like to contribute to this repository, please follow the [contributing guide](CONTRIBUTING.md).

## License
[Click here](LICENSE.md) for more information about the licensing of this module

## Issues 
To report issues with this module, please use our [issue tracker](../../issues). 