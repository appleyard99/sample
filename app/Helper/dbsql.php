<?php
/**
 * @detail 将底层执行的sql语句输入到sql.log 日志文件;
 */
if(!function_exists('saveSqlLog')){

    function saveSqlLog(){
        DB::enableQueryLog();
        if (class_exists(QueryExecuted::class, true)){
            DB::listen(function ($queryExecuted) {
                $query = $queryExecuted->sql;
                $bindings = $queryExecuted->bindings;
                $spend = $queryExecuted->time;
                $db = $queryExecuted->connectionName;
                $i = 0;
                $message = sprintf(
                    "query %s => %s ( %s ms )",
                    $db,
                    preg_replace_callback('/\?/', function ($matches) use ($bindings, &$i) {
                        if (! empty($bindings[$i])) {
                            return '\''.$bindings[$i++].'\'';
                        } else {
                            return '\''.'\'';
                        }

                    }, $query),
                    $spend
                );
                //用以laravel框架自带的日志系统写入日志会有问题:其他日志如error等系统log也会进入该文件;解决此问题要自己封装写入文件;
                /*Log::useFiles(storage_path().'/logs/sql.log');
                $messages=$db.':['.$message."] cost:".$spend;
                Log::info("sql : ",[$message]);*/
                //故自己封装日志文件写入
                $desLogFile = storage_path().'/logs/sql.log';
                if(!is_file($desLogFile)){
                    touch($desLogFile);
                    chmod($desLogFile,0777);
                }
                $date = date("Y-m-d H:i:s",time());
                $messages= "[ ".$date." ]: ".$db.':{'.$message."} ".PHP_EOL;
                file_put_contents($desLogFile,$messages,FILE_APPEND);
            });
        }else{
            DB::listen(function ($queryExecuted){
                $query = $queryExecuted->sql;
                $bindings = $queryExecuted->bindings;
                $spend = $queryExecuted->time;
                $db = $queryExecuted->connectionName;
                $i = 0;
                $message = sprintf(
                    "query %s => %s ( %s ms )",
                    $db,
                    preg_replace_callback('/\?/', function ($matches) use ($bindings, &$i) {
                        if (! empty($bindings[$i])) {
                            return '\''.$bindings[$i++].'\'';
                        } else {
                            return '\''.'\'';
                        }

                    }, $query),
                    $spend
                );
                 //用以laravel框架自带的日志系统写入日志会有问题:其他日志如error等系统log也会进入该文件;解决此问题要自己封装写入文件;
                /*Log::useFiles(storage_path().'/logs/sql.log');
                $messages=$db.':['.$message."] cost:".$spend;
                Log::info("sql : ",[$message]);*/
                //故自己封装日志文件写入
                $desLogFile = storage_path().'/logs/sql.log';
                if(!is_file($desLogFile)){
                    touch($desLogFile);
                    chmod($desLogFile,0777);
                }
                $date = date("Y-m-d H:i:s",time());
                $messages= "[ ".$date." ]: ".$db.':{'.$message."} ".PHP_EOL;
                file_put_contents($desLogFile,$messages,FILE_APPEND);

            });
        }

    }

}


