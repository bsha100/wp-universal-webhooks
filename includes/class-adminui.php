<?php

namespace WPUniversalWebhooks;

class AdminUI {

    public function __construct(){

        add_action('admin_menu',[$this,'menu']);
        add_action('admin_post_wpunw_save_webhook',[$this,'saveWebhook']);
        add_action('admin_post_wpunw_delete_webhook',[$this,'deleteWebhook']);

    }

    public function menu(){

        add_options_page(
            'WP Universal Webhooks',
            'Webhooks',
            'manage_options',
            'wpunw-settings',
            [$this,'page']
        );

    }

    public function page(){

        if(!current_user_can('manage_options')){
            return;
        }

        $events = [
            'post_published'=>'Post Published',
            'user_registered'=>'User Registered'
        ];

        $webhooks = get_option('wpunw_webhooks',[]);

        ?>

        <div class="wrap">
        <h1>WP Universal Webhooks</h1>

        <h2>Add New Webhook</h2>

        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

        <input type="hidden" name="action" value="wpunw_save_webhook">

        <?php wp_nonce_field('wpunw_save_webhook','wpunw_nonce'); ?>

        <table class="form-table">

        <tr>
        <th>Event</th>
        <td>

        <select name="event" required>

        <?php foreach($events as $key=>$label): ?>

        <option value="<?php echo esc_attr($key); ?>">
        <?php echo esc_html($label); ?>
        </option>

        <?php endforeach; ?>

        </select>

        </td>
        </tr>

        <tr>
        <th>Endpoint URL</th>
        <td>
        <input type="url" name="url" style="width:400px;" required>
        </td>
        </tr>

        <tr>
        <th>Headers (JSON)</th>
        <td>
        <textarea name="headers" rows="3" cols="60" placeholder='{"Authorization":"Bearer token"}'></textarea>
        </td>
        </tr>

        <tr>
        <th>Payload Template</th>
        <td>

        <textarea name="payload" rows="6" cols="60">
{
"post_id":"{post_id}",
"title":"{post_title}",
"url":"{post_url}"
}
        </textarea>

        <p class="description">
        Available variables depend on the event.
        </p>

        </td>
        </tr>

        </table>

        <button type="submit" class="button button-primary">
        Add Webhook
        </button>

        </form>

        <hr>

        <h2>Existing Webhooks</h2>

        <table class="widefat">

        <thead>

        <tr>
        <th>ID</th>
        <th>Event</th>
        <th>Endpoint</th>
        <th>Actions</th>
        </tr>

        </thead>

        <tbody>

        <?php if(empty($webhooks)): ?>

        <tr>
        <td colspan="4">No webhooks configured.</td>
        </tr>

        <?php else: ?>

        <?php foreach($webhooks as $id=>$hook): ?>

        <tr>

        <td><?php echo $id; ?></td>

        <td><?php echo esc_html($hook['event']); ?></td>

        <td><?php echo esc_url($hook['url']); ?></td>

        <td>

        <a class="button"
        href="<?php echo wp_nonce_url(
        admin_url('admin-post.php?action=wpunw_delete_webhook&id='.$id),
        'wpunw_delete_webhook'
        ); ?>">
        Delete
        </a>

        </td>

        </tr>

        <?php endforeach; ?>

        <?php endif; ?>

        </tbody>

        </table>

        <hr>

        <h2>Webhook Logs</h2>

        <?php

        $logs = array_reverse(get_option('wpunw_logs',[]));
        $logs = array_slice($logs,0,50);

        if(empty($logs)){

            echo "<p>No logs yet.</p>";

        } else {

            echo "<table class='widefat'>";

            echo "<thead><tr>
            <th>Time</th>
            <th>Event</th>
            <th>Endpoint</th>
            <th>Payload</th>
            </tr></thead>";

            echo "<tbody>";

            foreach($logs as $log){

                echo "<tr>";

                echo "<td>".esc_html($log['time'])."</td>";
                echo "<td>".esc_html($log['event'])."</td>";
                echo "<td>".esc_url($log['url'])."</td>";
                echo "<td><pre>".esc_html($log['payload'])."</pre></td>";

                echo "</tr>";

            }

            echo "</tbody></table>";

        }

        ?>

        </div>

        <?php

    }

    public function saveWebhook(){

        if(!current_user_can('manage_options') ||
        !check_admin_referer('wpunw_save_webhook','wpunw_nonce')){

            wp_die('Unauthorized');

        }

        $event = sanitize_text_field($_POST['event']);
        $url = esc_url_raw($_POST['url']);

        $headers = json_decode(stripslashes($_POST['headers']),true);
        $payload = stripslashes($_POST['payload']);

        $webhooks = get_option('wpunw_webhooks',[]);

        $id = time().rand(100,999);

        $webhooks[$id] = [
            'event'=>$event,
            'url'=>$url,
            'headers'=>$headers,
            'payload'=>$payload
        ];

        update_option('wpunw_webhooks',$webhooks);

        wp_redirect(admin_url('options-general.php?page=wpunw-settings'));
        exit;

    }

    public function deleteWebhook(){

        if(!current_user_can('manage_options') ||
        !check_admin_referer('wpunw_delete_webhook')){

            wp_die('Unauthorized');

        }

        $id = intval($_GET['id']);

        $webhooks = get_option('wpunw_webhooks',[]);

        if(isset($webhooks[$id])){

            unset($webhooks[$id]);

            update_option('wpunw_webhooks',$webhooks);

        }

        wp_redirect(admin_url('options-general.php?page=wpunw-settings'));
        exit;

    }

}