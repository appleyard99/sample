<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    /**用户授权策略添加步骤如下:
     * 一.为默认生成的用户授权策略UserPolicy添加 update 方法，用于用户更新时的权限验证。
     *第一个参数默认为当前登录用户实例，第二个参数则为要进行授权的用户实例。当两个 id 相同时，则代表两个用户是相同用户，
     * 用户通过授权，可以接着进行下一个操作。如果 id 不相同的话，将抛出 403 异常信息来拒绝访问。
     *使用授权策略需要注意以下两点：
     *1.我们并不需要检查 $currentUser 是不是 NULL。未登录用户，框架会自动为其 所有权限 返回 false；
     *2.调用时，默认情况下，我们 不需要 传递当前登录用户至该方法内，因为框架会自动加载当前登录用户（接着看下去，后面有例子）；
     * @param User $currentUser
     * @param User $user
     * @author mgg
     * @return bool
     */
    public function update(User $currentUser,User $user){
        return $currentUser->id ===$user->id;
    }
    /**二.接下来我们还需要在 AuthServiceProvider 类中对授权策略进行设置。AuthServiceProvider 包含了一个 policies 属性，该属性用于将各种模型对应到管理它们的授权策略上。
     * 我们需要为用户模型 User 指定授权策略 UserPolicy。
     * 三.授权策略定义完成之后，我们便可以通过在用户控制器中使用 authorize 方法来验证用户授权策略。默认的 App\Http\Controllers\Controller 类包含了 Laravel 的 AuthorizesRequests trait。此 trait 提供了 authorize 方法，它可以被用于快速授权一个指定的行为，当无权限运行该行为时会抛出 HttpException。authorize 方法接收两个参数,
     * 第一个为授权策略的名称，第二个为进行授权验证的数据。我们需要为 edit 和 update 方法加上这行：
     *$this->authorize('update', $user);
     */

    /**
     * 添加删除用户的用户策略:只有管理员能删除用户,且管理员不能将自己删除
     * Laravel 授权策略提供了 @can Blade 命令，允许我们在 Blade 模板中做授权判断。
     * 可以利用 @can 指令，在用户列表页加上只有管理员才能看到的删除用户按钮.
     *  如@can('destroy'(策略名), $user(User对象))@endcan
     * @param User $currentUser
     * @param User $user
     * @author mgg
     * @return bool
     */
    public function destroy(User $currentUser,User $user){

       return $currentUser->is_admin&&$currentUser->id!==$user->id ;

    }
}
