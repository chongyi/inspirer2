<?php
/**
 * MainController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {

    }

    public function content(Request $request, $type, $find)
    {
        switch ($type) {
            case 'articles':

            case 'pushed':
        }
    }
}