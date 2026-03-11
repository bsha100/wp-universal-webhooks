<?php

namespace WPUniversalWebhooks;

class WebhookManager {

    public function registerHooks(){

        add_action('publish_post', [$this,'postPublished'],10,2);
        add_action('user_register', [$this,'userRegistered'],10,1);

    }

    public function postPublished($post_ID,$post){

        $data = [
            'post_id'=>$post_ID,
            'post_title'=>$post->post_title,
            'post_url'=>get_permalink($post_ID)
        ];

        $this->sendWebhooks('post_published',$data);

    }

    public function userRegistered($user_id){

        $user = get_userdata($user_id);

        $data = [
            'user_id'=>$user_id,
            'user_email'=>$user->user_email
        ];

        $this->sendWebhooks('user_registered',$data);

    }

    public function sendWebhooks($event,$data){

        $webhooks = get_option('wpunw_webhooks',[]);

        foreach($webhooks as $hook){

            if($hook['event'] !== $event){
                continue;
            }

            $payload_template = $hook['payload'];

            foreach($data as $key=>$value){

                $payload_template = str_replace("{".$key."}",$value,$payload_template);

            }

            $headers = $hook['headers'] ?? ['Content-Type'=>'application/json'];

            wp_remote_post($hook['url'],[
                'body'=>$payload_template,
                'headers'=>$headers,
                'timeout'=>5
            ]);

            Logger::log($event,$payload_template,$hook['url']);

        }

    }

}