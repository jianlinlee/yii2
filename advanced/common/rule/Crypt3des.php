<?php

namespace common\rule;

/**
 * PHP版3DES加解密类
 * 可与java的3DES(DESede)加密方式兼容
 */
class Crypt3des
{
    public $key = "b28d5e43423b7cf4618abf6d4f984358";// key
    public $SD_KEY3DES = "C205BC5839533270jUN1d77Y";// key3des
    public $iv = "23456789"; //like java: private static byte[] myIV = { 50, 51, 52, 53, 54, 55, 56, 57 };

    public function sdencrypt($input,$key,$key3des) {
        var_dump($input);
        $input = json_encode($input);// json格式
        var_dump($input);die;
        $newinput = $input.$key;// 拼接key
        $md5input = md5($newinput);// md5加密
        $md5input = strtoupper($md5input);// 字符转大写
        $lastinput = $input.'|'.$md5input;// 拼接原参数与md5加密数据
//        $input = $this->padding( $lastinput );
//        var_dump($input);die;
//        $key3des = base64_decode($key3des);
        $td = mcrypt_module_open( MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        //使用MCRYPT_3DES算法,cbc模式
        mcrypt_generic_init($td, $key3des, $this->iv);
        //初始处理
        $data = mcrypt_generic($td, $lastinput);
        //加密
        mcrypt_generic_deinit($td);
        //结束
        mcrypt_module_close($td);
        $data = $this->removeBR(base64_encode($data));
        return $data;
    }

    //加密
    public function encrypt($input)
    {
        $input = $this->padding( $input );
        $key = base64_decode($this->key);
        $td = mcrypt_module_open( MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        //使用MCRYPT_3DES算法,cbc模式
        mcrypt_generic_init($td, $key, $this->iv);
        //初始处理
        $data = mcrypt_generic($td, $input);
        //加密
        mcrypt_generic_deinit($td);
        //结束
        mcrypt_module_close($td);
        $data = $this->removeBR(base64_encode($data));
        return $data;
    }

    //解密
    public function decrypt($encrypted)
    {
        $encrypted = base64_decode($encrypted);
        $key = base64_decode($this->key);
        $td = mcrypt_module_open( MCRYPT_3DES,'',MCRYPT_MODE_CBC,'');
        //使用MCRYPT_3DES算法,cbc模式
        mcrypt_generic_init($td, $key, $this->iv);
        //初始处理
        $decrypted = mdecrypt_generic($td, $encrypted);
        //解密
        mcrypt_generic_deinit($td);
        //结束
        mcrypt_module_close($td);
        $decrypted = $this->removePadding($decrypted);
        return $decrypted;
    }

    //填充密码，填充至8的倍数
    public function padding( $str )
    {
        $len = 8 - strlen( $str ) % 8;
        for ( $i = 0; $i < $len; $i++ )
        {
            $str .= chr( 0 );
        }
        return $str ;
    }

    //删除填充符
    public function removePadding( $str )
    {
        $len = strlen( $str );
        $newstr = "";
        $str = str_split($str);
        for ($i = 0; $i < $len; $i++ )
        {
            if ($str[$i] != chr( 0 ))
            {
                $newstr .= $str[$i];
            }
        }
        return $newstr;
    }

    //删除回车和换行
    public function removeBR( $str )
    {
        $len = strlen( $str );
        $newstr = "";
        $str = str_split($str);
        for ($i = 0; $i < $len; $i++ )
        {
            if ($str[$i] != '\n' and $str[$i] != '\r')
            {
                $newstr .= $str[$i];
            }
        }
        return $newstr;
    }

}