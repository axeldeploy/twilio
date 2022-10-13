# Twilio

[![Latest Stable Version](http://poser.pugx.org/axelhub/twilio/v)](https://packagist.org/packages/axelhub/twilio) [![Total Downloads](http://poser.pugx.org/axelhub/twilio/downloads)](https://packagist.org/packages/axelhub/twilio) [![Latest Unstable Version](http://poser.pugx.org/axelhub/twilio/v/unstable)](https://packagist.org/packages/axelhub/twilio) [![License](http://poser.pugx.org/axelhub/twilio/license)](https://packagist.org/packages/axelhub/twilio) [![PHP Version Require](http://poser.pugx.org/axelhub/twilio/require/php)](https://packagist.org/packages/axelhub/twilio)

### [Twilio sms sender](https://github.com/axeldeploy/twilio)

## Installation

<pre>composer require axelhub/twilio</pre>

After installing run command below. It will create `twilio.php` file in <i>config</i> folder.
<pre>php artisan twilio:install</pre>

Configure `twilio.php` file with Twilio key, secret, sid and from.
<br>
Also, you can customize the list of countries where the app will be able to send messages.
[List of countries.](https://www.twilio.com/guidelines/regulatory)

## Using

### Custom using

```php
$twilio = new Twilio();
$twilio->sendSms($PhoneNumber, $Message);
```

### Using with Laravel notification

[More about laravel notifications.](https://laravel.com/docs/notifications)

Add `SmsChannel::class` class into via function in notification:

```php
use Axel\Twilio\SmsChannel;

public function via($notifiable)
{
    return [SmsChannel::class];
}
```

Create `toSms()` function in notification class:

```php
use Axel\Twilio\SmsMessage;

public function toSms($notifiable): SmsMessage
{
    return (new SmsMessage)
        ->to($PhoneNumber)
        ->message($Message);
}
```
