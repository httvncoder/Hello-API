<?php

namespace App\Port\Provider\Providers;

use App\Port\Provider\Abstracts\ServiceProviderAbstract;
use App\Port\Provider\Traits\AutoRegisterServiceProvidersTrait;
use App\Port\Provider\Traits\PortServiceProviderTrait;
use App\Port\Routes\Providers\RoutesServiceProvider;


/**
 * Class PortServiceProvider
 * The main Task Provider where all Task Providers gets registered
 * this is the only Task Provider that gets injected in the Config/app.php.
 *
 * Class PortServiceProvider
 *
 * @author  Mahmoud Zalt <mahmoud@zalt.me>
 */
class PortServiceProvider extends ServiceProviderAbstract
{

    use PortServiceProviderTrait;
    use AutoRegisterServiceProvidersTrait;

    /**
     * the new Models Factories Paths
     */
    const MODELS_FACTORY_PATH = '/app/Port/Factory';

    /**
     * Port internal Task Provides.
     *
     * @var array
     */
    private $engineServiceProviders = [
        RoutesServiceProvider::class
    ];

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->registerServiceProviders(array_merge(
            $this->getContainersServiceProviders(),
            $this->engineServiceProviders
        ));

        $this->autoLoadViewsFromContainers();

        $this->overrideDefaultFractalSerializer();
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->changeTheDefaultDatabaseModelsFactoriesPath(self::MODELS_FACTORY_PATH);
        $this->publishContainersMigrationsFiles();
        $this->debugDatabaseQueries(true, true);
    }

}
