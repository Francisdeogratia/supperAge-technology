<?php

namespace App\Helpers;

class AgoraTokenBuilder
{
    const ROLE_PUBLISHER = 1;
    const ROLE_SUBSCRIBER = 2;

    public static function buildTokenWithUid(
        string $appId,
        string $appCertificate,
        string $channelName,
        int $uid,
        int $role = self::ROLE_PUBLISHER,
        int $privilegeExpiredTs = 0
    ): string {
        $token = new AccessToken($appId, $appCertificate);
        $serviceRtc = new ServiceRtc($channelName, (string)$uid);
        $serviceRtc->addPrivilege(ServiceRtc::PRIVILEGE_JOIN_CHANNEL, $privilegeExpiredTs);
        if ($role === self::ROLE_PUBLISHER) {
            $serviceRtc->addPrivilege(ServiceRtc::PRIVILEGE_PUBLISH_AUDIO_STREAM, $privilegeExpiredTs);
            $serviceRtc->addPrivilege(ServiceRtc::PRIVILEGE_PUBLISH_VIDEO_STREAM, $privilegeExpiredTs);
            $serviceRtc->addPrivilege(ServiceRtc::PRIVILEGE_PUBLISH_DATA_STREAM, $privilegeExpiredTs);
        }
        $token->addService($serviceRtc);
        return $token->build();
    }
}

class AccessToken
{
    const VERSION = '007';

    private string $appId;
    private string $appCert;
    private int $issueTs;
    private int $salt;
    private int $expire = 86400;
    private array $services = [];

    public function __construct(string $appId, string $appCert)
    {
        $this->appId = $appId;
        $this->appCert = $appCert;
        $this->issueTs = time();
        $this->salt = random_int(1, 99999999);
    }

    public function addService(Service $service): void
    {
        $this->services[$service->getServiceType()] = $service;
    }

    public function build(): string
    {
        if (!$this->appId || strlen($this->appId) !== 32) {
            return '';
        }
        if (!$this->appCert || strlen($this->appCert) !== 32) {
            return '';
        }

        $signing = $this->getSign();

        // Data includes appId (as packString), then issueTs, expire, salt, services
        $data = Packer::packString($this->appId)
            . Packer::packUint32($this->issueTs)
            . Packer::packUint32($this->expire)
            . Packer::packUint32($this->salt)
            . Packer::packUint16(count($this->services));

        ksort($this->services);
        foreach ($this->services as $service) {
            $data .= $service->pack();
        }

        $signature = hash_hmac('sha256', $data, $signing, true);

        // Content: signature + data (appId is already inside data)
        $content = Packer::packString($signature) . $data;
        $compressed = zlib_encode($content, ZLIB_ENCODING_DEFLATE);

        return self::VERSION . base64_encode($compressed);
    }

    private function getSign(): string
    {
        // Official: hmac(data=appCert, key=packUint32(issueTs))
        // then hmac(data=result, key=packUint32(salt))
        $hh = hash_hmac('sha256', $this->appCert, Packer::packUint32($this->issueTs), true);
        return hash_hmac('sha256', $hh, Packer::packUint32($this->salt), true);
    }
}

abstract class Service
{
    protected int $type;
    protected array $privileges = [];

    abstract public function getServiceType(): int;

    public function addPrivilege(int $privilege, int $expire): void
    {
        $this->privileges[$privilege] = $expire;
    }

    public function pack(): string
    {
        return Packer::packUint16($this->type) . $this->packPrivileges();
    }

    protected function packPrivileges(): string
    {
        $data = Packer::packUint16(count($this->privileges));
        foreach ($this->privileges as $key => $value) {
            $data .= Packer::packUint16($key) . Packer::packUint32($value);
        }
        return $data;
    }
}

class ServiceRtc extends Service
{
    const SERVICE_TYPE = 1;
    const PRIVILEGE_JOIN_CHANNEL = 1;
    const PRIVILEGE_PUBLISH_AUDIO_STREAM = 2;
    const PRIVILEGE_PUBLISH_VIDEO_STREAM = 3;
    const PRIVILEGE_PUBLISH_DATA_STREAM = 4;

    private string $channelName;
    private string $uid;

    public function __construct(string $channelName, string $uid)
    {
        $this->type = self::SERVICE_TYPE;
        $this->channelName = $channelName;
        $this->uid = $uid === '0' ? '' : $uid;
    }

    public function getServiceType(): int
    {
        return self::SERVICE_TYPE;
    }

    // Official order: parent::pack() (type + privileges) THEN channelName + uid
    public function pack(): string
    {
        return parent::pack()
            . Packer::packString($this->channelName)
            . Packer::packString($this->uid);
    }
}

class Packer
{
    public static function packUint16(int $val): string
    {
        return pack('v', $val);
    }

    public static function packUint32(int $val): string
    {
        return pack('V', $val);
    }

    public static function packString(string $val): string
    {
        return self::packUint16(strlen($val)) . $val;
    }
}
