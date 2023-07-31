<?php
namespace Majormfr\MajormfrWhitelist\Includes\Gates;

/**
 * fetch_whitelisted_plugins()
 * @return array of whitelisted domains
 */

class Gates
{
    public static $SAFETYGATEKEEPER_API_BASE_URL;
    public static function specifyWhitelistedUrl($ref_url){
    self::$SAFETYGATEKEEPER_API_BASE_URL = $ref_url;
    
    }
    public $installed_plugins;
    public static function fetch_plugins_bpd_from_db()
    {
        $active_plugins_option = get_option('active_plugins');

        if (!empty($active_plugins_option)) {

            return $active_plugins_option;
        }

        return array();
    }

    public static function fetch_whitelisted_plugins()
    {

        $url = self::$SAFETYGATEKEEPER_API_BASE_URL;

        $response = wp_remote_get($url);

        if (is_array($response) && !is_wp_error($response)) {
            $whitelisted_plugins = json_decode($response['body'], true);


            if (is_array($whitelisted_plugins)) {
                return $whitelisted_plugins;
            }
        }


        return array();
    }

    public function list_whitelisted_pages()
    {

        $url = self::$SAFETYGATEKEEPER_API_BASE_URL;
        $request_args = array(
            'method' => 'GET',
            'body' => array("domain" => $_SERVER['HTTP_HOST'])
        );

        $response = wp_remote_get($url, $request_args);
        if (is_wp_error($response)) {
            echo 'Error: ' . $response->get_error_message();
        } else {

            $response_code = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);
            if ($response_code == 404) {
                return;
            }
            $whitelisted_pages = json_decode($response_body)->whitelisted_pages->whitelisted_pages;
            
            if (!empty(trim($whitelisted_pages))) {
                $this->destroy_blacklisted_pages($whitelisted_pages);
            } else {
                $domain = $_SERVER['HTTP_HOST'];
                return array("message" => "No Restrictions for $domain");
            }


        }
    }

    public function destroy_blacklisted_pages($pages_list)
    {
        $whitelisted_slugs = explode(",", $pages_list);
        $posts = get_posts(array(
            'post_type' => array('post', 'page'),
            'post_status' => 'any',
            'numberposts' => -1, 
        ));
        $slugs = array();
        foreach ($posts as $page) {
            if (!in_array(get_page_uri($page->ID), $whitelisted_slugs)) {
                echo get_page_uri($page->ID) . "Should be deleted";
                $deleted = wp_delete_post($page->ID, true);

                if ($deleted) {
                    echo "Page with ID $page->ID has been successfully deleted.";
                } else {
                    echo "Failed to delete the page with ID $page->ID.";
                }
            }
        }
    }

    public static function disableUnknownPlugins(){

        global $whitelistPlugins;
    global $installed_plugins;
    global $plugin_error_slug;

    foreach ($installed_plugins as $k => $v) {
        if (!in_array($v, $whitelistPlugins)) {
            deactivate_plugins($v);
            $plugin_error_slug .= " and " . $v;
        }
    }


    }
    public static function display_plugin_error_alerts()
{
    global $plugin_error_slug;

    $error_message = "Error Activating $plugin_error_slug Blacklisted Plugin.";
    echo '<div class="error"><p>' . esc_html($error_message) . '</p></div>';
}
}


