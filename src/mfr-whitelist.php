<?php
namespace Majormfr\MajormfrWhitelist;

use Majormfr\MajormfrWhitelist\Includes\Gates\Gates;

$santizePlugin = new Gates();
$whitelistPlugins = $santizePlugin->fetch_whitelisted_plugins()['whitelist_plugins'] ?? [];
$installed_plugins = $santizePlugin->fetch_plugins_bpd_from_db();