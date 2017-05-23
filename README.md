# silverstripe-thankfully

If you're tired of having to continuously create dozens of different "Thank You" pages then _thankfully_ this module exists!

With this module you can easily create a dynamic "Thank You" page that is optionally configurable in the CMS for any page type that you assign this module to.

## Features
- You can configure the Thank You page title, content and URL from the CMS if the extension is added to the page type
- As a developer you can configure the Thank You page programmatically and redirect the user there
- Page creates itself on dev/build
- Query string support for conversion tracking etc, also configurable from the CMS

## Installation

Installation is supported via composer only:

```sh
$ composer require zanderwar/silverstripe-thankfully
```

Run a dev/build afterwards

## Extension

1. Create a `mysite/thankfully.yml` file
2. Add:
    ```yml
    ContactPage:
        extensions:
            - 'ThankfullyExtension'
            
    ContactPage_Controller:
        extensions:
            - 'ThankfullyControllerExtension'
    ```
3. Run a `?flush=1`
4. Open the page that has `ContactPage` (in this example) as the type and you will now see a "Thank You" tab

If the page title and/or content is not set in the page with the extension, it will fallback to the values set in the `Thank You (Generic)` that the dev/build created in the root of your site tree, if no defaults are configured, then the visitor will get a bit of an ugly experience

## Standalone

You can configure the root Thank You page simply like so:

```php
// ThankfullyPage::prepare($title, $content, $returnTo = null, array $queryStringPairs = array())
// $title: Represents the Title of the page
// $content: Represents the Content of the page
// $returnTo: Define a URL that a user should return to - This exposes a template method $ReturnTo which a developer can use to provide a button to return that user (optional)
// $queryStringPairs: Accepts an array of key/value pairs and will be built into a query string when Link() is called (optional)
$thanks = ThankfullyPage::prepare(
    array(
        'My Cool Title',
        '<p>We are quite thankful for whatever you have done to be brought here</p>'   
    )
);

return $this->redirect($thanks->Link());
```

## Contributing
If you would like to contribute to this repository, please follow the [contributing guide](CONTRIBUTING.md).

## License
[Click here](LICENSE.md) for more information about the licensing of this module

## Issues 
To report issues with this module, please use our [issue tracker](../../issues). 