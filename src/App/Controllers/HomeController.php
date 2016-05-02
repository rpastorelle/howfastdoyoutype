<?php
namespace App\Controllers;

use Core\BaseController;
use App\Models\Phrase;
use Core\Http\Exception\NotFoundException;

class HomeController extends BaseController
{
    public function index()
    {
        $data = [
            'phrase' => Phrase::findRandom(),
            'colors' => ['black','hotpink','burntorange','red','mustard','yellow','orange','green','blue','white'],
        ];

        return $this->view('index.html', $data);
    }
}