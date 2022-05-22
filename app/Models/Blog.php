<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getBlogs($id)
    {
        try {
            $query = $this->query();

            if (isset($id)) {
                $query->where('id', '=', $id);
            }

            return $query->get();

        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }
}
