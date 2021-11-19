<?php

declare(strict_types=1);

namespace Vitagon\SerializerBug\dto;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UpdateRequest implements DenormalizableInterface
{
    /**
     * @var array<int, User>
     */
    private array $users;

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    /**
     * @throws ExceptionInterface
     */
    public function denormalize(
        DenormalizerInterface $denormalizer,
        $data,
        string $format = null,
        array $context = []
    ): UpdateRequest {
        $users = [];
        foreach ($data['users'] as $userId => $user) {
            $denormalizedUser = $denormalizer->denormalize($user, User::class, $format, $context);
            $users[$userId] = $denormalizedUser;
        }

        $dto = new self();
        $dto->setUsers($users);

        return $dto;
    }
}
