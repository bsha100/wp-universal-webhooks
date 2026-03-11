# WP Universal Webhooks

WP Universal Webhooks is a WordPress plugin that allows you to send WordPress events to any external service using webhooks.

Instead of building custom integrations for every new service, this plugin provides a **flexible, centralized system** for triggering webhooks on key WordPress actions.

---

## Features

* Trigger webhooks on WordPress events such as posts being published or new users registering
* Multiple endpoints per event
* Customizable JSON payloads
* Optional authentication headers
* Logging of webhook requests for easy debugging
* Lightweight and developer-friendly

---

## Why Use This Plugin?

Developers and agencies often need to integrate WordPress with external systems (CRM, Slack, analytics, SaaS tools). WP Universal Webhooks eliminates the need to write custom code for each integration.

It’s ideal for:

* Membership sites
* WooCommerce stores
* Form plugins
* Custom workflows

---

## Installation

1. Download or clone this repository
2. Upload the plugin folder to:

```id="gxn9dl"
/wp-content/plugins/wp-universal-webhooks
```

3. Activate the plugin from the WordPress **Plugins** page

---

## Usage

1. Navigate to **Settings → Webhooks**
2. Add endpoints for events like `post_published` or `user_registered`
3. Configure headers or custom JSON payloads as needed
4. View logs to confirm webhook deliveries

---

## Future Improvements

* Add support for **custom post types and WooCommerce events**
* Advanced **payload templating**
* Retry failed webhook deliveries
* Integration with popular services (Zapier, Integromat, etc.)

---

## License

GPL v2 or later

---

## Contributing

Contributions are welcome! If you want to suggest additional triggers or improve logging, open an issue or submit a pull request.
