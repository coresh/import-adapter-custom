# M2IF - Custom Adapter

## Requirements

* PHP 7.2+
* M2IF >= 3.8.0-beta2

## Setup

To make the custom adapter run two simple steps are necessary.

### Step 1: Installation

You can either use the PHAR file or add M2IF to your Magento installation by adding


```json
{
  "require": {
    "techdivision/import-adapter-custom": "dev-master as 1.0.0"
  }
}
```

to your `composer.json` file.


### Step 2: Configuration

Copy the `etc` directory from the root directory of this library to the `app/etc/` directory of your Magento installation, e. g.

```sh
cd <magento install directory> && cp -r vendor/techdivision/import-custom-adapter/etc/* app/etc/
```

### Step 3: Run

You're now ready to run the custom adapter. Aassuming that the HTML file `data/some.html` will be reachable via your localhost
like `https://localhost/some.html` enter the following command 

```sh
vendor/bin/import-simple import:products --params='{"params":{"url":"https://localhost/some.html"}}'
```

whereas the url has to point to the HTML file the additional data will be located in. 
