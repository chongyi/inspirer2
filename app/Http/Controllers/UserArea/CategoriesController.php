<?php
/**
 * CategoriesController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\UserArea;


use App\Http\Controllers\Controller;
use App\Repositories\Content\ContentNodeChannel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriesController extends Controller
{
    public function index()
    {
        $channel = ContentNodeChannel::findByName('category');

        if (is_null($channel)) {
            throw (new ModelNotFoundException())->setModel(ContentNodeChannel::class);
        }

        return $channel->getRootNodesByChannelId();
    }
}