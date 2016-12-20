<?php
/**
 * Created by PhpStorm.
 * User: BRUCE
 * Date: 2016/12/20
 * Time: 12:26
 *使用方法：
 * include此页面
 * new Mem
 *   调用addServer添加服务器
 *   调用s方法（一个参数：获取，三个参数：设置，两个参数+NULL删除）；
 */
     class Mem
     {
         private $type='Memcached';
         private $m;
         private $time=0;
         private $error;
         private $debug='true';
         public function __construct()
         {
             if(!class_exists($this->type)){
                 $this->error='No'.$this->type;
                 return false;
             }else{
                 $this->m=new $this->type;
             }
         }

         public function addServer($arr){
             $this->m->addServers($arr);
         }
         public function s($key,$value='',$time=0){
             $number=func_num_args();
             if($number==1){
                return $this->get($key);
             }else if($number>=2){
                   if($value===Null){
                      $this->delete($key);
                   }else{
                       $this->set($key,$value,$time);
                   }
             }
         }
         //设置
         private function set($key,$value,$time=NULL){
             if($time===NULL) {
                 $time=$this->time;
             }
             $this->m->set($key,$value,$time);
             if($this->debug=='true') {
                 if($this->m->getResultCode() !==0){
                     return false;
                 }
             }

         }
        //获取
         private function get($key){
            $result=$this->m->get($key);
            if($this->debug){
                if($this->m->getResultCode()!==0){
                    return false;
                }else{
                    return $result;
                }
            } else{
                return $result;
            }
         }
         //删除
         private function delete($key){
               $this->m->delete($key);
         }
         public function  getError()
         {
             if($this->error){
                 return $this->error;
             }else{
                 return $this->m->getResultMessage();
             }
         }
     }