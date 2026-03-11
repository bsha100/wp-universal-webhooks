<?php

namespace WPUniversalWebhooks;

class WebhookManager {

    public function __construct(){
        // Hook into some example WordPress events
        add_action('publish_post', [$this,'triggerPostPublished'], 10, 2);
        add_action('user_register', [$this,'triggerUserRegistered'], 10, 1);
    }

    public function triggerPostPublished($post_ID, $post){

        $payload = [
            'post_id' => $post_ID,
            'post_title' => $post->post_title,
            'post_url' => get_permalink($post_ID)
        ];

        $this->sendWebhooks('post_published', $payload);

    }

    public function triggerUserRegistered($user_id){

        $user = get_userdata($user_id);
        $payload = [
            'user_id' => $user_id,
            'user_email' => $user->user_email
        ];

        $this->sendWebhooks('user_registered', $payload);

    }

    public function sendWebhooks($event, $payload){

        $endpoints = get_option("wpunw_endpoints_{$event}", []);

        foreach($endpoints as $endpoint){

            wp_remote_post($endpoint['url'], [
                'body' => json_encode($payload),
                'headers' => $endpoint['headers'] ?? ['Content-Type'=>'application/json'],
                'timeout' => 5
            ]);

            // Log the request
            Logger::log($event, $payload, $endpoint['url']);
        }

    }

}