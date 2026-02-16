<?php

namespace App\Repositories\User;

interface UserInterface
{
    public function getAll(array $options);

    public function getById($id);

    public function getByEmail($email);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);
}