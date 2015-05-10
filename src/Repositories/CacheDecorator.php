<?php
namespace TypiCMS\Modules\Pages\Repositories;

use Illuminate\Support\Facades\Input;
use TypiCMS\Modules\Core\Repositories\CacheAbstractDecorator;
use TypiCMS\Modules\Core\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements PageInterface
{

    public function __construct(PageInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Get page by uri
     *
     * @param  string                      $uri
     * @param  string                      $locale
     * @return TypiCMS\Modules\Models\Page $model
     */
    public function getFirstByUri($uri, $locale)
    {
        $cacheKey = md5(config('app.locale').'getFirstByUri.'.$uri.$locale.implode('.', Input::all()));

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $model = $this->repo->getFirstByUri($uri, $locale);

        // Store in cache for next request
        $this->cache->put($cacheKey, $model);

        return $model;
    }


    /**
     * Get submenu for a page
     *
     * @return Collection
     */
    public function getSubMenu($uri, $all = false)
    {
        $cacheKey = md5(config('app.locale').'getSubMenu.'.$uri.$all);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $models = $this->repo->getSubMenu($uri, $all);

        // Store in cache for next request
        $this->cache->put($cacheKey, $models);

        return $models;
    }

    /**
     * Get pages linked to module to build routes
     *
     * @return array
     */
    public function getForRoutes()
    {
        $cacheKey = md5(config('app.locale') . 'getForRoutes');

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $models = $this->repo->getForRoutes();

        // Store in cache for next request
        $this->cache->put($cacheKey, $models);

        return $models;
    }
}
