<?php

if (!function_exists('json_encode')) {
    throw new Exception('Vero needs the JSON PHP extension.');
}

require(dirname(__FILE__) . '/vero/client.php');

/**
 * Class Vero
 */
class Vero
{
    /**
     * @var Vero\Client
     */
    private $client;

    /**
     * Vero constructor.
     * @param  $auth_token
     *
     * @throws Exception
     */
    public function __construct($auth_token)
    {
        if (!$auth_token)
            throw new Exception("VeroClient auth_token parameter is required");

        $this->client = new Vero\Client($auth_token);
    }

    /**
     * @param  $user_id
     * @param  null $email
     * @param  array $data
     *
     * @return bool|string
     *
     * @throws Exception
     */
    public function identify($user_id, $email = null, $data = [])
    {
        if (!$user_id)
            throw new Exception("Vero::Identify requires a user id");

        return $this->client->identify($user_id, $email, $data);
    }

    /**
     * @param $user_id
     * @param $new_user_id
     * @return bool|string
     * @throws Exception
     */
    public function reidentify($user_id, $new_user_id)
    {
        if (!$user_id || !$new_user_id)
            throw new Exception("Vero::Reidentify requires a user id AND a new user id");

        return $this->client->reidentify($user_id, $new_user_id);
    }

    /**
     * @param $user_id
     * @param array $changes
     * @return bool|string
     * @throws Exception
     */
    public function update($user_id, $changes = [])
    {
        if (!$user_id)
            throw new Exception("Vero::Update requires a user id");

        if ($changes == [])
            throw new Exception("Vero::Update requires changes param");

        return $this->client->update($user_id, $changes);
    }

    /**
     * @param $user_id
     * @param array $add
     * @param array $remove
     * @return bool|string
     * @throws Exception
     */
    public function tags($user_id, $add = [], $remove = [])
    {
        if (!$user_id)
            throw new Exception("Vero::Tags requires a user id");

        if ($add == [] && $remove == [])
            throw new Exception("Vero::Update requires either add or remove param");

        return $this->client->tags($user_id, $add, $remove);
    }

    /**
     * @param $user_id
     * @param $email
     * @return bool|string
     * @throws Exception
     */
    public function unsubscribe($user_id, $email)
    {
        if ($user_id && $email)
            throw new Exception("Vero::Unsubscribe requires either user id or email param");

        if (!$user_id && !$email)
            throw new Exception("Vero::Unsubscribe requires a user id or email");

        return $this->client->unsubscribe($user_id, $email);
    }

    /**
     * @param $user_id
     * @param $email
     * @return bool|string
     * @throws Exception
     */
    public function resubscribe($user_id, $email)
    {
        if ($user_id && $email)
            throw new Exception("Vero::Resubscribe requires either user id or email param");

        if (!$user_id && !$email)
            throw new Exception("Vero::Resubscribe requires a user id or email");

        return $this->client->resubscribe($user_id, $email);
    }

    /**
     * @param $event_name
     * @param array $identity
     * @param array $data
     * @param array $extras
     * @return bool|string
     * @throws Exception
     */
    public function track($event_name, $identity = [], $data = [], $extras = [])
    {
        if (!$event_name)
            throw new Exception("Vero::Track requires an event name");

        if (($identity == []) || ((gettype($identity) == 'array') && (!$identity['id'])))
            throw new Exception("Vero::Update requires an identity profile with at least an id property");

        return $this->client->track($event_name, $identity, $data, $extras);
    }

}

?>
