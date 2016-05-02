<?php
namespace App\Models;

use Core\Database\Model;

class User extends Model
{
    public static function transform($phrase = [])
    {
        return array_merge($phrase, [
            'type' => ucwords($phrase['type']),
            'lc_phrase' => strtolower($phrase['phrase']),
            'length' => strlen($phrase['phrase']),
        ]);
    }

    public static function createNew($values = [])
    {
        $insert = static::$app->query->newInsert();
        $insert->into('users')
               ->cols([
                   'timestamp' => time(),
                   'username' => array_get($values, 'username'),
               ]);

        // prepare the statement + execute with bound values
        $sth = static::$app->db->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());

        $id = static::$app->db->lastInsertId();

        return $id;
    }

    public static function update($userId, $values = [])
    {
        $update = static::$app->query->newUpdate();
        $update->table('users')
               ->cols([
                   'username' => array_get($values, 'username'),
               ])
               ->where('id = ?', $userId);

        // prepare the statement + execute with bound values
        $sth = static::$app->db->prepare($update->getStatement());
        $sth->execute($update->getBindValues());

        return $userId;
    }

    public static function generateUsername()
    {
        $query = static::$app->query->newSelect();
        $query->cols(['Auto_increment'])
              ->from('information_schema.tables')
              ->where('table_name="users"')
              ->where('table_schema="'. static::$app->getSetting('db.name') .'"');

        $nextId = static::$app->db->fetchValue($query);
        return 'user'.$nextId;
    }
}