<?php

declare(strict_types=1);

namespace App\Security\Admin;

use App\Security\AbstractUserDbHelper;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminDbHelper extends AbstractUserDbHelper
{
    protected function getUserTableColumns(): array
    {
        return [
            'id',
            'email',
            'password_hash',
            'name',
            'surname',
            'roles',
        ];
    }

    protected function mapDataToModel(array $data): UserInterface
    {
        try {
            return new AdminReadModel(
                $data['id'],
                $data['email'],
                $data['password_hash'],
                $data['name'],
                $data['surname'],
                \json_decode($data['roles'], true, 512, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $exception) {
            throw new UserNotFoundException($exception->getMessage());
        } catch (\Throwable) {
            throw new UserNotFoundException();
        }
    }
}
