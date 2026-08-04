<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateUUID'];
    
    protected function generateUUID(array $data): array
    {
        if (empty($data['data'][$this->primaryKey])) {
            $data['data'][$this->primaryKey] = service('uuid')->uuid4()->toString();
        }

        return $data;
    }
}
