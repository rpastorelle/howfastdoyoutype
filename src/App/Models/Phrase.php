<?php
namespace App\Models;

use Core\Database\Model;

class Phrase extends Model
{
    public static function transform($phrase = [])
    {
        return array_merge($phrase, [
            'type' => ucwords($phrase['type']),
            'lc_phrase' => strtolower($phrase['phrase']),
            'length' => strlen($phrase['phrase']),
        ]);
    }

    public static function findRandom()
    {
        $query = static::$app->query->newSelect();
        $query->cols(['*'])
              ->from('phrases')
              ->orderBy(['rand()'])
              ->limit(1);

        $phrase = static::$app->db->fetchOne($query);
        if (! $phrase) {
            return null;
        }

        return static::transform($phrase);
    }
}