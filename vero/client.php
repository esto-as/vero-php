<?php

namespace Vero;

/**
 * Class Client
 * @package Vero
 */
class Client
{

    /**
     * @var
     */
    private $auth_token;

    /**
     * Client constructor.
     *
     * @param $auth_token
     */
    public function __construct($auth_token)
    {
        $this->auth_token = $auth_token;
    }

    /**
     * @param  $user_id
     * @param  $email
     * @param  $data
     *
     * @return bool|string
     */
    public function identify($user_id, $email, $data)
    {
        $endpoint = "https://api.getvero.com/api/v2/users/track.json";
        $request_data = [
            'auth_token' => $this->auth_token,
            'id'         => $user_id,
            'data'       => ($data == [] ? NULL : $data),
        ];

        if ($email)
            $request_data['email'] = $email;

        return $this->_send($endpoint, $request_data);
    }

    /**
     * @param  $user_id
     * @param  $new_user_id
     *
     * @return bool|string
     */
    public function reidentify($user_id, $new_user_id)
    {
        $endpoint = "https://api.getvero.com/api/v2/users/reidentify.json";
        $request_data = array(
            'auth_token' => $this->auth_token,
            'id'         => $user_id,
            'new_id'     => $new_user_id,
        );

        return $this->_send($endpoint, $request_data, 'put');
    }

    /**
     * @param  $user_id
     * @param  $changes
     *
     * @return bool|string
     */
    public function update($user_id, $changes)
    {
        $endpoint = "https://api.getvero.com/api/v2/users/edit.json";
        $request_data = [
            'auth_token' => $this->auth_token,
            'id'         => $user_id,
            'changes'    => $changes,
            'channels'   => $changes['channels'] ?? NULL,
        ];

        return $this->_send($endpoint, $request_data, 'put');
    }

    /**
     * @param  $user_id
     * @param  $add
     * @param  $remove
     *
     * @return bool|string
     */
    public function tags($user_id, $add, $remove)
    {
        $endpoint = "https://api.getvero.com/api/v2/users/tags/edit.json";
        $request_data = [
            'auth_token' => $this->auth_token,
            'id'         => $user_id,
            'add'        => $add,
            'remove'     => $remove,
        ];

        return $this->_send($endpoint, $request_data, 'put');
    }

    /**
     * @param  $user_id
     * @param  $email
     *
     * @return bool|string
     */
    public function unsubscribe($user_id, $email)
    {
        $endpoint = "https://api.getvero.com/api/v2/users/unsubscribe.json";
        $request_data = [
            'auth_token' => $this->auth_token,
            'id'         => $user_id,
            'email'      => $email,
        ];

        return $this->_send($endpoint, $request_data);
    }

    /**
     * @param  $user_id
     * @param  $email
     *
     * @return bool|string
     */
    public function resubscribe($user_id, $email)
    {
        $endpoint = "https://api.getvero.com/api/v2/users/resubscribe.json";
        $request_data = [
            'auth_token' => $this->auth_token,
            'id'         => $user_id,
            'email'      => $email,
        ];

        return $this->_send($endpoint, $request_data);
    }

    /**
     * @param  $event_name
     * @param  $identity
     * @param  $data
     * @param array $extras
     *
     * @return bool|string
     */
    public function track($event_name, $identity, $data, $extras = [])
    {
        $endpoint = "https://api.getvero.com/api/v2/events/track.json";
        $request_data = [
            'auth_token' => $this->auth_token,
            'identity'   => $identity,
            'event_name' => $event_name,
            'data'       => ($data == [] ? NULL : $data),
            'extras'     => ($extras == [] ? NULL : $extras),
        ];

        return $this->_send($endpoint, $request_data);
    }

    /**
     * @param string $endpoint
     * @param array $request_data
     * @param string $request_type
     *
     * @return bool|string
     */
    private function _send($endpoint, $request_data, $request_type = 'post')
    {
        $request_data = json_encode($request_data);
        $headers = ['Accept: application/json', 'Content-Type: application/json'];
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $endpoint);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        $request_type === 'post' ?
            curl_setopt($handle, CURLOPT_POST, true) :
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($handle, CURLOPT_POSTFIELDS, $request_data);

        $result = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return $result;
    }

}

?>
