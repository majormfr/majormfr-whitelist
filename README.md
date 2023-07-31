
# Security Gatekeeper

Library to remove unauthorized plugins.
Specify url that with following route

https://{my_domain}.com/whitelist/plugins

Above should return an array with key "whitelist_plugins"

Install Composer in wordpress project



## Installation

To deploy this project run

```bash
  composer init
```
Install package

```bash
  composer require majormfr/majormfr-whitelist
```

In your custom plugin or functions.php file 
```bash
    Gates::specifyWhitelistedUrl("https://{my_domains}.com/whitelist/plugins");

   $whitelistPlugins = Gates::fetch_whitelisted_plugins()['whitelist_plugins'] ?? [];
    $installed_plugins = Gates::fetch_plugins_bpd_from_db();
    
```
and
```bash
  if (count($whitelistPlugins) > 0) {
        echo count($whitelistPlugins);
        add_action('admin_init', array('Majormfr\MajormfrWhitelist\Includes\Gates\Gates', 'disableUnknownPlugins'));
    }
```
