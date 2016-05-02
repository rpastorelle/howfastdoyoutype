<?php

use Phinx\Seed\AbstractSeed;

class PhrasesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // inserting multiple rows
        $phrases = [
            [
              'id' => 1,
              'name' => 'The Original',
              'phrase' => 'The quick brown fox jumps over the lazy dog',
              'type' => 'pangram',
            ],
            [
              'id' => 2,
              'name' => 'Liquor Jugs',
              'phrase' => 'Pack my box with five dozen liquor jugs',
              'type' => 'pangram',
            ],
            [
              'id' => 3,
              'name' => 'Zippy Fowls',
              'phrase' => 'A very bad quack might jinx zippy fowls',
              'type' => 'pangram',
            ],
            [
              'id' => 4,
              'name' => 'Frozen Wives',
              'phrase' => 'A quick chop jolted my big sexy frozen wives',
              'type' => 'pangram',
            ],
            [
              'id' => 5,
              'name' => 'The Trebek',
              'phrase' => 'Watch "Jeopardy!", Alex Trebek\'s fun TV quiz game',
              'type' => 'pangram',
            ],
            [
              'id' => 6,
              'name' => 'J-Lo\'s Quiche',
              'phrase' => 'Foxy diva Jennifer Lopez wasn\'t baking my quiche',
              'type' => 'pangram',
            ],
            [
              'id' => 7,
              'name' => 'GQ Jock',
              'phrase' => 'GQ jock wears vinyl tuxedo for showbiz promo',
              'type' => 'pangram',
            ],
            [
              'id' => 8,
              'name' => 'Zombie Graveyard',
              'phrase' => 'Painful zombies quickly watch a jinxed graveyard',
              'type' => 'pangram',
            ],
            [
              'id' => 9,
              'name' => 'Grumpy Wizards',
              'phrase' => 'Grumpy wizards make toxic brew for the evil Queen and Jack',
              'type' => 'pangram',
            ],
            [
              'id' => 10,
              'name' => 'Black Taxis',
              'phrase' => 'Few black taxis drive up major roads on quiet hazy nights',
              'type' => 'pangram',
            ],
            [
              'id' => 11,
              'name' => 'Enemy Gunboats',
              'phrase' => 'A quick movement of the enemy will jeopardize six gunboats',
              'type' => 'pangram',
            ],
            [
              'id' => 12,
              'name' => 'Ebonics Quiz',
              'phrase' => '"Who am taking the ebonics quiz?", the prof jovially axed',
              'type' => 'pangram',
            ],
            [
              'id' => 13,
              'name' => 'July Quakes',
              'phrase' => 'Big July earthquakes confound zany experimental vow',
              'type' => 'pangram',
            ],
            [
              'id' => 14,
              'name' => 'The Southpaw',
              'phrase' => 'Dear grease fader create safe red dead ace cards',
              'type' => 'left-handed',
            ],
            [
              'id' => 15,
              'name' => 'Joe\'s Lament',
              'phrase' => 'Lament at how Joe vaporized big foxy quacks',
              'type' => 'pangram',
            ],
        ];
        // this is a handy shortcut
        $this->insert('phrases', $phrases);

    }
}
