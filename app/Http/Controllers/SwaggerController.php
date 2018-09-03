<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Swagger\Annotations as SWG;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="xxxxxx.com",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Web端 API",
 *         description="描述信息后期填写",
 *         termsOfService="",
 *         @SWG\Tag(name="User", description="用户模块"),
 *         @SWG\Tag(name="Session", description="登录模块"),
 *         @SWG\Contact(
 *             email="xxxxxxxxx@163.com"
 *         ),
 *     )
 * )
 */
class SwaggerController extends Controller
{

    public function doc()
    {

        $swagger = \Swagger\scan(realpath(__DIR__.'/../../'));
        return response()->json($swagger);
    }
}
