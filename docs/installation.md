# Stan Connect Installation

1. Run

    ```bash
    $ composer require stan-business/sylius-stan-connect-plugin
    ```

2. Import Routes

    ```yaml
    # config/routes/sylius_shop.yaml

    brightweb_sylius_stan_connect_shop:
        resource: "@BrightwebSyliusStanConnectPlugin/config/shop_routing.yml"

    # config/routes/sylius_admin.yaml

    brightweb_sylius_stan_connect_admin:
        resource: "@BrightwebSyliusStanConnectPlugin/config/admin_routing.yml"
    ```
    
3. Import configuration

    ```yaml
   # config/packages/_sylius.yaml

   imports:
       # ...
       - { resource: "@SyliusPayPalPlugin/config/config.yaml" }
   ```

4. Add dependencies

Add plugin dependencies to your config/bundles.php file:

    ```php
    // config/bundles.php
    return [
        ...
        Brightweb\SyliusStanConnectPlugin::class => ['all' => true],
        ...
    ]
    ```

5. Apply migrations

   ```
   bin/console doctrine:migrations:migrate -n
   ```

# Configuration

You will only need to create a free [Stan account](https://compte.stan-app.fr), and get your API credentials.
