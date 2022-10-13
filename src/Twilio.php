<?php

namespace Axel\Twilio;

use Axel\Twilio\Exceptions\NotAllowedCountryException;
use Axel\Twilio\Exceptions\NotAllowedTypeException;
use Axel\Twilio\Exceptions\NotValidNumberException;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class Twilio
{
    private $twilio;
    private $from;
    private $countries;
    private $skipTwilioCheck;

    /**
     * @throws ConfigurationException
     */
    public function __construct()
    {
        $key = config('twilio.key');
        $secret = config('twilio.secret');
        $sid = config('twilio.sid');
        $this->from = config('twilio.from');
        $this->countries = config('twilio.countries');
        $this->skipTwilioCheck = config('twilio.skip_twilio_check') ?: false;

        if (
            is_null($key) ||
            is_null($secret) ||
            is_null($sid) ||
            is_null($this->from) ||
            empty($this->countries)
        ) {
            throw new ConfigurationException('Configurations are incorrect');
        }

        $this->twilio = new Client($key, $secret, $sid);
    }

    /**
     * @param $to
     * @param $message
     * @param null $from
     * @return MessageInstance
     * @throws NotAllowedCountryException
     * @throws NotAllowedTypeException
     * @throws NotValidNumberException
     * @throws TwilioException
     */
    public function sendSms($to, $message, $from = null): MessageInstance
    {
        if (empty($to)) {
            throw new TwilioException("Recipient is not specified");
        }

        if (empty($message)) {
            throw new TwilioException("Message is not specified");
        }

        $this->validateNumber($to);

        if (!empty($from)) {
            $this->from = $from;
        }

        return $this->twilio->messages->create($to, [
            'from' => $this->from,
            'body' => $message
        ]);
    }

    /**
     * @param $to
     * @throws NotAllowedCountryException
     * @throws NotAllowedTypeException
     * @throws NotValidNumberException
     * @throws TwilioException
     */
    private function validateNumber($to)
    {
        if (empty($to) || !preg_match('/^\+\d/', $to)) {
            throw new NotValidNumberException('An empty or incorrect number was sent');
        }

        if (!$this->skipTwilioCheck) {
            $lookup = $this->twilio->lookups->v2->phoneNumbers($to);
            $number = $lookup->fetch(["type" => ["carrier"]]);

            if (empty($number->toArray())) {
                throw new NotAllowedTypeException('Sent phone carrier was not found');
            }

            if (!$number->valid) {
                throw new NotAllowedTypeException('Sent phone number is invalid');
            }

            if (!in_array($number->countryCode, $this->countries)) {
                throw new NotAllowedCountryException('Unable to send SMS to numbers in this country');
            }
        }
    }
}
