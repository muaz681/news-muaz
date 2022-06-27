<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GoInstall extends Command
{
    protected $signature = 'go:install';

    protected $description = 'Install News Application';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $requirements = $this->checkRequirements(); // check requirements
        $phpVersion = $this->checkPHPversion(); // check PHP version
        $composerInstall = $this->composerInstall();  // check PHP Composer

        foreach ($requirements['requirements'] as $type => $require) {
            foreach ($requirements['requirements'][$type] as $extention => $enabled) {
                if ($enabled ? $src = "Ok" : $src = "Not Supported") {
                    $this->info($extention . ": " . $src);
                }
            }
        }

        if ($phpVersion['supported'] ? $src = "Ok" : $src = "Not Supported") {
            $this->info("PHP version : " . $src);
        }
        if (($composerInstall['composer_install'] == 0) ? $src = "Installed" : $src = "Not Installed") {
            $this->info("Composer : " . $src);
        }

        // check for Api .env
        $this->warn("Check for Api env");
        $this->checkForApiEnv();

        $this->warn("Check for Frontend env");
        $this->checkForFrontendEnv();

        // check for nuxt.config.js
        $this->warn("Check for Backend Config");
        $this->checkNuxtConfig();

        // loading values at runtime
        $this->loadEnvConfigAtRuntime();

        /* setting for the first time and then `.env` values will be incharged */
        $db_name = $this->getEnvAtRuntime('DB_DATABASE');
        $db_user = $this->getEnvAtRuntime('DB_USERNAME');
        $db_pass = $this->getEnvAtRuntime('DB_PASSWORD');
        if ($db_name && $db_user && $db_pass != null) {
            // running `php artisan migrate`
            $this->warn('Step: Migrating all tables into database...');
            $migrate = $this->call('migrate:fresh');
            $this->info($migrate);
        } else {
            $this->warn('Enter All Database Information');
            $this->addDatabaseDetails();
            $this->warn('Step: Migrating all tables into database...');
            $migrate = $this->call('migrate:fresh');
            $this->info($migrate);
        }

        // running `php artisan db:seed`
        $this->warn('Step: Seeding basic data for News Application...');
        $result = $this->call('db:seed');
        $this->info($result);

        // running `php artisan vendor:publish --all`
        $this->warn('Step: Publishing assets and configurations...');
        $result = $this->call('vendor:publish', ['--all' => true, '--force' => true]);
        $this->info($result);

        // running `php artisan storage:link`
        $this->warn('Step: Linking storage directory...');
        $result = $this->call('storage:link');
        $this->info($result);

        // optimizing stuffs
        $this->warn('Step: Optimizing...');
        $result = $this->call('optimize');
        $this->info($result);

        // running `composer dump-autoload`
        $this->warn('Step: Composer autoload...');
        $result = shell_exec('composer dump-autoload -d api');
        $this->info($result);
        $result = shell_exec('composer dump-autoload -d frontend');
        $this->info($result);

        //install npm
        $result = shell_exec('npm install --prefix backend'); 
        $this->info($result);

        //build nuxt backend
        $result = shell_exec('npm run build --prefix backend'); 
        $this->info($result);

        // final information
        $this->info('-----------------------------');
        $this->info('Congratulations!');
        $this->info('The configuration is finished. You can access admin using: Email: admin@news.com and Password: admin123');
        $this->info('Cheers!');
    }
    //check for .env
    protected function checkForApiEnv()
    {
        $envExists = File::exists(base_path() . '/.env');
        if (!$envExists) {
            $this->info('Creating the environment configuration file.');
            $this->createApiEnv();
        } else {
            $this->info('Great! your environment configuration file already exists.');
        }

        $this->call('key:generate');
        $this->call('jwt:secret');
    }
    protected function checkForFrontendEnv()
    {
        $base_path = base_path();
        $path = str_replace('api', 'frontend/.env', $base_path);
        $envExists = File::exists($path);
        if (!$envExists) {
            $this->info('Creating the environment configuration file.');
            $this->createFrontendEnv();
        } else {
            $this->info('Great! your environment configuration file already exists.');
        }

        $frontend = str_replace('api', 'frontend/artisan', base_path());
        $frontend_key =  exec($frontend . ' key:generate');
        $this->info("Frontend Key : " . $frontend_key);
    }

    // check nuxt.config.js
    protected function checkNuxtConfig()
    {
        $base_path = base_path();
        $nuxt = str_replace('api', 'backend/nuxt.config.js', $base_path);
        $nuxt_env = str_replace('api', 'backend/.env', $base_path);
        $nuxtConfig = File::exists($nuxt);
        $nuxtEnv = File::exists($nuxt_env);

        if (!$nuxtConfig && !$nuxtEnv) {
            $this->info('Creating the Backend .env.');
            $this->createBackendEnv();
        } else {
            $this->info('Great! your Backend .env file file already exists.');
        }
    }

    //create api env
    protected function createApiEnv()
    {
        $this->warn('Please enter required information for Api:');
        try {
            File::copy('api/.env.example', 'api/.env');

            $default_app_url =  'http://localhost:8000';
            $input_app_url = $this->ask('Please enter the Api url: ');
            $this->envUpdate('api/.env', 'APP_URL=', $input_app_url ? $input_app_url : $default_app_url);
            $this->envUpdate('api/.env', 'APP_TIMEZONE=', date_default_timezone_get());
            $this->addDatabaseDetails();
        } catch (\Exception $e) {
            $this->error('Error in creating .env file, please create it manually and then run `php artisan migrate` again.');
        }
    }
    //create frontend env
    protected function createFrontendEnv()
    {
        $this->warn('Please enter required information for Frontend:');
        try {
            File::copy('frontend/.env.example', 'frontend/.env');

            $app_url = $this->getEnvAtRuntime('APP_URL');
            $env_path = 'frontend/.env';
            $this->envUpdate($env_path, 'APP_URL=', $app_url);
            $this->envUpdate($env_path, 'APP_URL=', $app_url);
            $this->envUpdate($env_path, 'ASSET_URL=', $app_url);

            $default_app_url =  'http://localhost:8000';
            $input_app_url = $this->ask('Please Enter the Frontend URL : ');
            $this->envUpdate($env_path, 'WEB_URL=', $input_app_url ? $input_app_url : $default_app_url);
            $this->envUpdate($env_path, 'APP_TIMEZONE=', date_default_timezone_get());
            $this->envUpdate($env_path, 'DB_DATABASE=', $this->getEnvAtRuntime('DB_DATABASE'));
            $this->envUpdate($env_path, 'DB_USERNAME=', $this->getEnvAtRuntime('DB_USERNAME'));
            $this->envUpdate($env_path, 'DB_PASSWORD=', $this->getEnvAtRuntime('DB_PASSWORD'));

        } catch (\Exception $e) {
            $this->error('Error in creating Frontend .env file, please create it manually and then run `php artisan migrate` again.');
        }
    }

    //create Backend .env 
    protected function createBackendEnv()
    {
        try {
            File::copy('backend/nuxt.config.js.example', 'backend/nuxt.config.js');
            File::copy('backend/.env.example', 'backend/.env');

            $this->info('Backend .env Created');

            $default_app_url =  'http://localhost:8001';
            $input_app_url = $this->ask('Please enter the Admin Pannel url: ');
            $this->envUpdate('backend/.env', 'ADMIN_URL=', $input_app_url ? $input_app_url : $default_app_url);

            $api_url = $this->getEnvAtRuntime('APP_URL');

            $app_url = strval($this->getEnvAtRuntime('APP_URL')) . "api/v0/";
            $this->envUpdate('backend/.env', 'API_HOST=', $app_url);

        } catch (\Exception $e) {
            $this->error('Error in creating Backend .env file, please create it manually.');
        }
    }

    //Api database details
    protected function addDatabaseDetails()
    {
        $dbName = $this->ask('What is the database name to be used by News?');
        $this->envUpdate('api/.env', 'DB_DATABASE=', $dbName);

        $dbUser = $this->anticipate('What is your database username?', ['root']);
        $this->envUpdate('api/.env', 'DB_USERNAME=', $dbUser);

        $dbPass = $this->secret('What is your database password?');
        $this->envUpdate('api/.env', 'DB_PASSWORD=', $dbPass);
    }

    //Load `.env` config at runtime.
    protected function loadEnvConfigAtRuntime()
    {
        $this->warn('Loading configs...');

        /* environment directly checked from `.env` so changing in config won't reflect */
        app()['env'] = $this->getEnvAtRuntime('APP_ENV');

        /* setting for the first time and then `.env` values will be incharged */
        config(['database.connections.mysql.database' => $this->getEnvAtRuntime('DB_DATABASE')]);
        config(['database.connections.mysql.username' => $this->getEnvAtRuntime('DB_USERNAME')]);
        config(['database.connections.mysql.password' => $this->getEnvAtRuntime('DB_PASSWORD')]);
        DB::purge('mysql');

        $this->info('Configuration loaded..');
    }

    /**
     * Check key in `.env` file because it will help to find values at runtime.
     */
    protected static function getEnvAtRuntime($key)
    {
        $path = base_path() . '/.env';
        $data = file($path);

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);
                $rowValues = explode('=', $line);

                if (strlen($line) !== 0) {
                    if (strpos($key, $rowValues[0]) !== false) {
                        return $rowValues[1];
                    }
                }
            }
        }

        return false;
    }

    //Update the .env values.
    protected static function envUpdate($envpath ,$key, $value)
    {   
        $base_path = base_path();
        $path = str_replace('api', $envpath, $base_path);
        $data = file($path);       
        $keyValueData = $changedData = [];

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);
                $rowValues = explode('=', $line);

                if (strlen($line) !== 0) {
                    $keyValueData[$rowValues[0]] = $rowValues[1];

                    if (strpos($key, $rowValues[0]) !== false) {
                        $keyValueData[$rowValues[0]] = $value;
                    }
                }
            }
        }

        foreach ($keyValueData as $key => $value) {
            $changedData[] = $key . '=' . $value;
        }

        $changedData = implode(PHP_EOL, $changedData);

        file_put_contents($path, $changedData);
    }
    public function composerInstall(): array
    {
        $location = str_replace('\\', '/', getcwd());
        $currentLocation = explode("/", $location);
        array_pop($currentLocation);
        array_pop($currentLocation);
        $desiredLocation = implode("/api", $currentLocation);
        $autoLoadFile = $desiredLocation . '/api' . 'vendor' . '/' . 'autoload.php';

        if (file_exists($autoLoadFile)) {
            $data['composer_install'] = 0;
        } else {
            $data['composer_install'] = 1;
            $data['composer'] = 'We have detected that the required composer dependencies are not installed.<br />Go to the root directory of Api Directory and run "composer install".';
        }

        return $data;
    }
    private static function getPhpVersionInfo(): array
    {
        $currentVersionFull = PHP_VERSION;
        preg_match("#^\d+(\.\d+)*#", $currentVersionFull, $filtered);
        $currentVersion = $filtered[0];

        return [
            'full' => $currentVersionFull,
            'version' => $currentVersion
        ];
    }
    public function checkPHPVersion(): array
    {
        /**
         * Minimum PHP Version Supported (Override is in installer.php config file).
         *
         * @var string _minPhpVersion
         */
        $_minPhpVersion = '7.4.0';

        $currentPhpVersion = $this->getPhpVersionInfo();
        $supported = false;

        if (version_compare((str_pad($currentPhpVersion['version'], 6, "0")), $_minPhpVersion) >= 0) {
            $supported = true;
        }

        return [
            'full' => $currentPhpVersion['full'],
            'current' => $currentPhpVersion['version'],
            'minimum' => $_minPhpVersion,
            'supported' => $supported
        ];
    }
    public function checkRequirements(): array
    {
        // Server Requirements
        $requirements =  [
            'php' => [
                'openssl',
                'pdo',
                'mbstring',
                'tokenizer',
                'JSON',
                'cURL',
            ],
            // 'apache' => [
            //     'mod_rewrite',
            // ]
        ];

        $results = [];

        foreach ($requirements as $type => $requirement) {
            switch ($type) {
                    // check php requirements
                case 'php':
                    foreach ($requirements[$type] as $requirement) {
                        $results['requirements'][$type][$requirement] = true;

                        if (!extension_loaded($requirement)) {
                            $results['requirements'][$type][$requirement] = false;

                            $results['errors'] = true;
                        }
                    }
                    break;
            }
        }

        return $results;
    }
}