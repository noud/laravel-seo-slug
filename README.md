# Laravel Slug

Decorate your Models with SEO Slug.

## Requirements

* PHP 7.2+
* Laravel 5.6+

## Installation

Install the package by running this command in your terminal/cmd:
```
composer require noud/laravel-slug
```

## Usage

Here is a usage example. First add the Slug logic to your models. Then create and do a migration.

### Model

Add Slug business logic to a Model, like so:

```
<?php

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use SEO\Models\Traits\Slug;

class BlogPosting extends Eloquent
{
    use Slug;
    
    private $url;

    public function generateSlug() {
        // whatever logic you find appropriate
        $urlParts = explode('/', $this->url);
        end($urlParts);
        return prev($urlParts);
    }
}
```

### Migration

Create a migration with the classes you want to decorate with a Slug, like so ```database/migrations/yyy_mm_dd_hhmmss_slugged_tables.php```:
```
<?php

use SEO\AddSlugToTables;

class SlugedTables extends AddSlugToTables
{
    protected $models = [
        'BlogPosting',
        'JobPosting',
    ];
}
```

Run migrations as usual.

## Result

Now an url like ```https://seo.localhost/blog_posting/2/schema_org``` will change to ```https://seo.localhost/blog_posting/hackathon-tilburg-groot-succes/schema_org```.