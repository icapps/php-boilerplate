<?php

namespace App\EventListener\Api;

use App\Entity\User;
use App\Repository\DeviceRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseAuthenticator;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JwtCreatedListener implements EventSubscriberInterface
{
    public const AUTH_DEVICE_REQUIRED_FIELDS = ['deviceSid', 'deviceToken'];

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var DeviceRepository
     */
    private $deviceRepository;

    /**
     * @param RequestStack $requestStack
     * @param DeviceRepository $deviceRepository
     */
    public function __construct(RequestStack $requestStack, DeviceRepository $deviceRepository)
    {
        $this->requestStack = $requestStack;
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $jsonEncoder = new JsonEncoder();
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return;
        }

        /** @var string $requestContent */
        $requestContent = $request->getContent();
        $data = $jsonEncoder->decode($requestContent, JsonEncoder::FORMAT);

        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }

        // Update user device(s).
        if (count(array_intersect_key(array_flip(self::AUTH_DEVICE_REQUIRED_FIELDS), $data)) == count(self::AUTH_DEVICE_REQUIRED_FIELDS)) {
            $device = $this->deviceRepository->findOneBy([
                'user' => $user,
                'deviceId' => $data['deviceSid']
            ]);

            if (!$device) {
                $device = $this->deviceRepository->create();
            }

            $device->setUser($user);
            $device->setDeviceId($data['deviceSid']);
            $device->setDeviceToken($data['deviceToken']);

            try {
                $this->deviceRepository->save($device);
            } catch (OptimisticLockException | ORMException $e) {
                // Silence.
            }
        }

        // Format response.
        $data = $event->getData();
        $response = [
            'token' => $data['token'] ?? null,
            'refreshToken' => $data['refreshToken'] ?? null,
        ];

        $event->setData($response);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccessResponse',
        );
    }
}
