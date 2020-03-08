<?php namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';

    protected $allowedFields = ['id',	'name',	'first_name',	'email',	'creation_date',	'is_admin'];
}
