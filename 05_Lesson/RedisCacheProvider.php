<?php


namespace App\Providers;

use Illuminate\Support\Facades\Redis;

class RedisCacheProvider
{
    private $connection = null;
    private function getConnection(){
        if($this->connection===null){
            $this->connection = Redis::connection();
        }
        return $this->connection;
    }
    public function get($key){
        $result = false;
        if($c = $this->getConnection()){
            $result = $c->get($key);
        }
        return $result;
    }
    public function set($key, $value, $time=0){
        if($c=$this->getConnection()){
            $c->set($key, $value, $time);
        }
    }
    public function del($key){
        if($c=$this->getConnection()){
            $c->del($key);
        }
    }
    public function clear(){
        if($c=$this->getConnection()){
            $c->flushDB();
        }
    }
}
