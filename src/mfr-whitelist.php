<?php
namespace Majormfr\MajormfrWhitelist;

use Majormfr\MajormfrWhitelist\Includes\Gates\Gates;


define('SAFETYGATEKEEPER_PATH', plugin_dir_path(__FILE__));
define('SAFETYGATEKEEPER_URL', plugin_dir_url(__FILE__));
define('SAFETYGATEKEEPER_API_BASE_URL', 'https://bpdmonitors.com');

$santizePlugin = new Gates();
$whitelistPlugins = $santizePlugin->fetch_whitelisted_plugins()['whitelist_plugins'] ?? [];
$installed_plugins = $santizePlugin->fetch_plugins_bpd_from_db();