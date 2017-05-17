<?php

namespace App\Support\Deploy;

use Composer\Script\Event;

class ComposerScripts
{
    /**
     * Services to be bond.
     *
     * @var array
     */
    public static $services = [
        [
            'service_key'  => 'compose-for-mysql',
            'service_name' => "Compose for MySQL-fo",
            'env_mapping'  => [
                3 => 'DB_USERNAME',
                5 => 'DB_PASSWORD',
                6 => 'DB_HOST',
                8 => 'DB_PORT',
            ]
        ],
    ];

    /**
     * Handle the post-install Composer event.
     *
     * @param  \Composer\Script\Event $event
     *
     * @return void
     */
    public static function postInstall(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';

        self::setEnv();
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param  \Composer\Script\Event $event
     *
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';

        self::setEnv();
    }

    /**
     * Copy env and set values.
     *
     * @return void
     */
    public static function setEnv()
    {
        // We are on bluemix
        if (self::onBluemix()) {
            // Copy Env values
            self::copyEnv();

            // Set services credentials
            self::setServiceCredentials();
        }
    }

    /**
     * Determine if we are on Bluemix.
     *
     * @return bool
     */
    public static function onBluemix()
    {
        // If VCAP_APPLICATION env is there
        // this means we are on Bluemix
        return getenv('VCAP_APPLICATION') !== false;
    }

    /**
     * Copy .env for the correct environment.
     *
     * @return void
     */
    public static function copyEnv()
    {
        // Get VCAP_APPLICATION
        $vcapsApplication = json_decode(getenv('VCAP_APPLICATION'), true);
        // Copy env of the appropriate space
        if (isset($vcapsApplication['space_name'])) {
            copy(
                '.env.' . strtolower($vcapsApplication['space_name']),
                '.env'
            );
        }
    }

    /**
     * Setup all Service Credentials.
     *
     * @return void
     */
    public static function setServiceCredentials()
    {
        // Get all bound services
        $allBoundServices = collect(json_decode(getenv('VCAP_SERVICES'), true));

        collect(self::$services)->each(function ($service) use ($allBoundServices) {
            // Determine whether we have the specific service key
            // among all bound services.
            if ($allBoundServices->has($service['service_key'])) {
                // Collect from bound services under this service key
                $services = collect($allBoundServices->get($service['service_key']));

                // Get the specific bound service
                $boundService = collect($services->where('name', $service['service_name'])->first());

                $uri = collect($boundService->get('credentials'))->get('uri');
                $matches = array();

                if (!preg_match('/^(mysql[:][\/]{2})?(([a-z0-9_-]+)([:]([a-z0-9_-]*))?[@])?([^:^\/]+)(:([\d]+))?([\/]([a-z0-9_-]+))$/i', $uri, $matches)) {
                    return;
                }

                // Save each credentials in .env file
                collect($service['env_mapping'])->each(function ($envKey, $credentialKey) use ($matches) {
                    self::saveValueToEnv(
                        $envKey,
                        collect($matches)->get($credentialKey)
                    );
                });
            }
        });
    }

    /**
     * Save a value to the .env file.
     *
     * @param string $envKey
     * @param string $value
     *
     * @return void
     */
    public static function saveValueToEnv($envKey, $value)
    {
        file_put_contents(
            '.env',
            str_replace($envKey . '=', $envKey . '=' . $value, file_get_contents('.env'))
        );
    }
}
