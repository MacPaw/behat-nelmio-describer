Behat Nelmio Describer
=================================

| Version | Build Status | Code Coverage |
|:---------:|:-------------:|:-----:|
| `master`| [![CI][master Build Status Image]][master Build Status] | [![Coverage Status][master Code Coverage Image]][master Code Coverage] |
| `develop`| [![CI][develop Build Status Image]][develop Build Status] | [![Coverage Status][develop Code Coverage Image]][develop Code Coverage] |

Installation
============

Step 1: Install Bundle
----------------------------------
Open a command console, enter your project directory and execute:

```console
$ composer require --dev macpaw/behat-nelmio-describer
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
----------------------------------
Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            BehatApiDocDescriber\BehatApiDocDescriberBundle::class => ['all' => true]
        );

        // ...
    }

    // ...
}
```

Step 3: Create Behat Nelmio Describer Config:
----------------------------------
`config/packages/behat_nelmio_describer.yaml `

Configurating behat nelmio describer

```yaml
behat_api_doc_describer:
  behat_test_path: <path to directory with your behat features>
```

Step 4: Add annotation to controller [OPTIONAL]
=============

```php
<?php

use BehatApiDocDescriber\Attributes\BehatFeaturesPath;

#[BehatFeaturesPath(path: "<path to folder/file with fixtures regarding base path in config>")]
final class SomeController extends AbstractController{
    // ... 
}
```

Step 5: Add annotation to route
=============

```php
<?php

use BehatApiDocDescriber\Attributes\BehatFeature;
use BehatApiDocDescriber\Enum\Status;

final class SomeController extends AbstractController{
    #[BehatFeature(status: "<string name to group by>", file: '<filename or route to file regarding base path>', anchors: [
       // array of anchors    
    ])]
    public function handleRequestFunction() {
        // ...
    }
}
```

For each anchor path from config, path from BehatFeaturesPath annotation (optional) and path/filename from BehatFeature annotation are concatenated to find the right feature file.

Additionally, each BehatFeature annotation represents folder in api doc which contains all sample responses defined by anchors.

[master Build Status]: https://github.com/macpaw/behat-nelmio-describer/actions?query=workflow%3ACI+branch%3Amaster
[master Build Status Image]: https://github.com/macpaw/behat-nelmio-describer/workflows/CI/badge.svg?branch=master
[develop Build Status]: https://github.com/macpaw/behat-nelmio-describer/actions?query=workflow%3ACI+branch%3Adevelop
[develop Build Status Image]: https://github.com/macpaw/behat-nelmio-describer/workflows/CI/badge.svg?branch=develop
[master Code Coverage]: https://codecov.io/gh/macpaw/behat-nelmio-describer/branch/master
[master Code Coverage Image]: https://img.shields.io/codecov/c/github/macpaw/behat-nelmio-describer/master?logo=codecov
[develop Code Coverage]: https://codecov.io/gh/macpaw/behat-nelmio-describer/branch/develop
[develop Code Coverage Image]: https://img.shields.io/codecov/c/github/macpaw/behat-nelmio-describer/develop?logo=codecov
