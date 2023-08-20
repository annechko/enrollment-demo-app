<?php

declare(strict_types=1);

namespace App\Security\Student;

use App\Security\AbstractUserDbHelper;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class StudentDbHelper extends AbstractUserDbHelper
{
    protected function getUserTableColumns(): array
    {
        return [
            'id',
            'email',
            'password_hash',
            'name',
            'surname',
            'is_email_verified',
            'roles',
        ];
    }

    protected function mapDataToModel(array $data): UserInterface
    {
        try {
            return new StudentReadModel(
                $data['id'],
                $data['email'],
                $data['password_hash'],
                $data['name'],
                $data['surname'],
                $data['is_email_verified'],
                \json_decode($data['roles'], true, 512, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $exception) {
            throw new UserNotFoundException($exception->getMessage());
        } catch (\Throwable) {
            throw new UserNotFoundException();
        }
    }
}
