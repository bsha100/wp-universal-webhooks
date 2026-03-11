<?php

namespace WPUniversalWebhooks;

class Plugin {

    public function init(){

        $manager = new WebhookManager();
        new AdminUI($manager);

    }

}