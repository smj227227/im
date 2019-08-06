<?php
/*
    *author:123
    *time 2019/4/22 3:08 PM
    *All rights reserved
*/
namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;

class SocketServer  extends Command
{

    protected $signature = 'work:socket {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'worker socket';

    /**
     * SocketServer constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author mjShu
     */
    public function handle()
    {
        global $argv;
        $action = $this->argument('action');
        $argv[0]='worker:socket';
        $argv[1]=$action;
        $argv[2]=$this->option('d')?'-d':'';
        $context = array(
            'ssl' => array(
                'local_cert'  => '/home/wwwroot/im.caomei520.com/ssl/im.caomei520.com.crt', // 或者crt文件
                'local_pk'    => '/home/wwwroot/im.caomei520.com/ssl/im.caomei520.com.key',
                'verify_peer' => false
            )
        );
        $Gateway = new Gateway('websocket://0.0.0.0:9006',$context);
        $Gateway->transport = 'ssl';

//        $Gateway = new Gateway('websocket://0.0.0.0:8082');


        new Register('text://0.0.0.0:1238');
        $Gateway->name = 'Gateway';
        $Gateway->count = 1;
        $Gateway->lanIp = '127.0.0.1';
        $Gateway->startPort = 10000;
        $Gateway->pingInterval = 1000;  //10s一次心跳
        $Gateway->registerAddress = '127.0.0.1:1238';
        $Gateway->pingNotResponseLimit = 3000;
        $Gateway->pingData = '';
        $worker = new BusinessWorker();
        $worker->eventHandler = 'App\Http\Controllers\WebSocket\WebSocketController';
        $worker->name = 'BusinessWorker';
        $worker->count = 3;
        $worker->registerAddress = '127.0.0.1:1238';
        Gateway::runAll();
    }

}