<?php

namespace Axel\Twilio;

use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;

class SmsMessage
{
    protected $from = null;
    protected $message;
    protected $to;

    public function from($from): SmsMessage
    {
        $this->from = $from;

        return $this;
    }

    public function to($to): SmsMessage
    {
        $this->to = $to;

        return $this;
    }

    public function message($message): SmsMessage
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @throws TwilioException
     */
    public function send(): MessageInstance
    {
        $twilio = new Twilio();
        return $twilio->sendSms($this->to, $this->message, $this->from);
    }
}