# Notifier

The `ds_notifier` extension for TYPO3 CMS provides a flexible notification system that can handle various notification channels like Email and Slack (upcoming). It allows for dynamic event handling and custom notification configurations.

## Features

- **Dynamic Event Handling**: Utilize custom events to trigger notifications.
- **Multiple Notification Channels**: Supports Email, and potentially more channels in the future (Slack, ...).
- **Event-Driven Notifications**: Configure notifications based on system or custom events.
- **Localization Support**: Comes with built-in support for translations.

## Requirements

- PHP 8.3 or higher
- TYPO3 CMS 12.4 or higher

## Installation

1. Install the extension via Composer:
   ```bash
   composer require devskio/ds_notifier
   ```

2. Run database compare to add the new tables.

## Usage

#### Built-in events

##### TYPO3: Cache Flush
The [CacheFlush](./Classes/Event/Typo3/Core/Cache/CacheFlush.php) event is triggered when TYPO3's caching system is flushed. This can occur during various system maintenance tasks or via explicit backend actions. Handling this event allows developers to notify system administrators when the cache is cleared.

##### Notifier: Notification Send Error
The [NotificationSendError](./Classes/Event/Notifier/NotificationSendError.php) event is triggered when there is an error during the notification sending process. This event allows for handling errors specifically related to the notification system, such as logging errors, sending alerts to administrators, or attempting to resend notifications. It provides a mechanism to robustly manage failures in the notification delivery process.


##### Form: Submit Finisher
The [SubmitFinisherEvent](./Classes/Event/Form/SubmitFinisherEvent.php) adds new [Form Framework finisher](https://docs.typo3.org/p/typo3/cms-form/main/en-us/DeveloperGuide/Finishers/Index.html) which is triggered at the end of the form submission process in TYPO3's form framework. This event allows for custom actions to be executed after a form has been successfully submitted and processed. It is particularly useful for integrating additional notifications related to form submissions.

## Configuration

The extension can be configured via TYPO3's backend configuration modules. You can set up different notification channels, templates, and more.

## Development

Developers can extend the functionality by creating custom event handlers or notification channels.

### Extending Notification Channels

Implement the `NotificationInterface` in your custom channel class and define the sending logic.


## Support

For support, contact the development team at [DEVSK](https://www.devsk.io/).

## License

This project is licensed under the GPL-2.0-or-later. See the `LICENSE` file for more details.
