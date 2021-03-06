<?php

namespace SEO\Models\Traits;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait Slug
{
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->generateSlug())
            ->saveSlugsTo($this->getRouteKeyName())    // @todo const in Trait
            ->allowDuplicateSlugs()    // @todo danger, not unique
            ->usingLanguage('nl')
        ;
    }
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}