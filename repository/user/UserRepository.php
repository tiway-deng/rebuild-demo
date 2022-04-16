<?php
namespace app\repository\user;

use App\domain\user\Specification\UserSpecificationInterface;
use app\domain\user\User;
use App\domain\user\valueObject\UserId;

class UserRepository implements UserSpecificationInterface
{
    private $model;
    public function __construct(\app\models\User $user)
    {
        $this->model = $user;
    }
    public function getOne(UserId $id) : User
    {
        return $this->model->findOne($id);
    }
}