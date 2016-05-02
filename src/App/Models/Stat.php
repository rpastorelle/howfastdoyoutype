<?php
namespace App\Models;

use Core\Database\Model;

class Stat extends Model
{
    public static function transform($stats = [], $append = [])
    {
        $id = $stats['id'];
        $phraseId = $stats['phrase_id'];
        $color = $stats['color'];
        return array_merge($stats, $append, [
            'rank' => static::getRank($id, $phraseId),
            'avgs' => static::getAvgByPhrase($phraseId, $color),
        ]);
    }

    public static function getRank($statId, $phraseId)
    {
        $ranks = [];
        $query = static::$app->query->newSelect();
        $query->cols(['id', '@curRow := @curRow + 1 as row_number'])
              ->from('stats')
              ->fromSubSelect('(SELECT @curRow := 0)', 'r')
              ->where('phrase_id='.$phraseId)
              ->where('isDNQ=0')
              ->orderBy(['nwpm DESC']);
        $res = static::$app->db->fetchAll($query);
        foreach ($res as $row) {
            $id = $row['id'];
            $ranks[$id] = $row['row_number'];
        }

        return [
            'position' => (int) $ranks[$statId],
            'total' => count($ranks),
        ];
    }

    public static function getAvgByPhrase($phraseId, $color)
    {
        if (! $phraseId) return [];

        // Fetch averages:
        $query = static::$app->query->newSelect();
        $query->cols(['avg(milliseconds) as ms', 'avg(wpm) as wpm', 'avg(nwpm) as nwpm', 'avg(errors) as errors'])
              ->from('stats')
              ->where('phrase_id='.$phraseId)
              ->where('isDNQ=0')
              ->groupBy(['phrase_id']);
        $avgs = static::$app->db->fetchOne($query);

        // Fetch color_nwpm:
        $query = static::$app->query->newSelect();
        $query->cols(['avg(nwpm)'])
              ->from('stats')
              ->where('color="'.$color.'"')
              ->where('isDNQ=0')
              ->groupBy(['color']);
        $color_nwpm = static::$app->db->fetchValue($query);

        return array_merge($avgs, [
            'color_nwpm' => $color_nwpm,
        ]);
    }

    public static function findById($id)
    {
        $query = static::$app->query->newSelect();
        $query->cols(['*'])
              ->from('stats')
              ->where('id='.$id);

        $stats = static::$app->db->fetchOne($query);
        if (! $stats) {
            return null;
        }

        return $stats;
    }

    public static function createNew($values = [])
    {
        $insert = static::$app->query->newInsert();
        $insert->into('stats')
               ->cols([
                   'phrase_id' => array_get($values, 'phrase_id'),
                   'user_id' => array_get($values, 'user_id'),
                   'milliseconds' => array_get($values, 'milliseconds'),
                   'wpm' => array_get($values, 'wpm'),
                   'nwpm' => array_get($values, 'nwpm'),
                   'errors' => array_get($values, 'errors'),
                   'color' => array_get($values, 'color'),
                   'isMobile' => array_get($values, 'isMobile') == 'true',
                   'isTablet' => array_get($values, 'isTablet') == 'true',
                   'timestamp' => time(),
                   'isDNQ' => array_get($values, 'isDNQ'),
               ]);

        // prepare the statement + execute with bound values
        $sth = static::$app->db->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());

        $id = static::$app->db->lastInsertId();

        return $id;
    }
}