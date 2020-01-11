# Laravel SEO Slug

Decorate your Models with SEO Slug.

## Requirements

* PHP 7.2+
* Laravel 5.6+

## Installation

Install the package by running this command in your terminal/cmd:
```
composer require noud/laravel-seo-slug
```

## Usage

Here is a usage example. First add the Slug logic to your models. Then create and do a migration.

### Models

Add Slug business logic to Models, like so:

```
<?php

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use SEO\Models\Traits\Slug;

class BlogPosting extends Eloquent
{
    use Slug;
    
    private $url;   // par exemple

    public function generateSlug() {
        // whatever logic you find appropriate
        $urlParts = explode('/', $this->url);
        end($urlParts);
        return prev($urlParts);
    }
}
```

The default Slug database column name is ```slug``` but can be overwritten.

```
<?php

class BlogPosting extends Eloquent
{
    public function getRouteKeyName()
    {
        return 'sluggish';
    }
}
```

### Migration

Create a migration to decorate with the Slugs, like so ```database/migrations/yyy_mm_dd_hhmmss_slugged_tables.php``` as your last migration:
```
<?php

use SEO\AddSlugToTables;

class SlugedTables extends AddSlugToTables
{  
}
```

Run migrations as usual.

## Result

Now an url like ```https://seo.localhost/blog_posting/2``` will change to ```https://seo.localhost/blog_posting/hackathon-tilburg-groot-succes```.