<?php
/**
 * OperationRejectedException.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Exceptions;

/**
 * Class OperationRejectedException
 *
 * 操作被拒绝异常
 *
 * 该异常一般是进行非法操作（即在不允许的状态条件下进行操作）。这个异常有别于因权限问题导致的失败，
 * 该异常属于逻辑错误，例如数据未保存时就进行对应查询。
 *
 * @package App\Exceptions
 */
class OperationRejectedException extends \LogicException
{

}