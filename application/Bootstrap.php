<?php

use \Illuminate\Database\Capsule\Manager;

/**
 * Bootstrap 
 * 
 * @uses Yaf\Bootstrap_Abstract
 * @version ${Id}$
 * @author Shaowei Pu <pushaowei@sporte.cn>
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{
    /**
     * [_initConfig 初始化配置]
     * @author         Shaowei Pu <pushaowei@sporte.cn>
     * @CreateTime    2016-12-22T17:49:38+0800
     * @return                              [type] [description]
     */
    public function _initConfig() 
    {
        //把配置保存起来
        $arrConfig = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $arrConfig);        
        //关闭自动加载模板目录
        Yaf\Dispatcher::getInstance()->autoRender(FALSE);
        Yaf\Dispatcher::getInstance()->throwException(TRUE);
        Yaf\Dispatcher::getInstance()->catchException(TRUE);

        // ini
        ini_set('session.cookie_domain', YaConf::get('MconfInit.global.session_domain'));
        ini_set("session.save_path"    , YaConf::get('MconfInit.global.session_path'));

    }

    /**
     * [_initPlugin 初始化各插件]
     * @author         Shaowei Pu <pushaowei@sporte.cn>
     * @CreateTime    2016-12-22T17:49:50+0800
     * @param                               Yaf\Dispatcher $dispatcher [description]
     * @return                              [type]                     [description]
     */
    public function _initPlugin(Yaf\Dispatcher $dispatcher) {
        //注册一个插件
         $loader = Yaf\loader::getInstance();
         $loader->import("/plugins/ManagoDispatch.php");
         $ManagoDispatch = new ManagoDispatchPlugin();
         $dispatcher->registerPlugin($ManagoDispatch);
    }

    /**
     * [_initRoute 路由初始化]
     * @author         Shaowei Pu <pushaowei@sporte.cn>
     * @CreateTime    2016-12-22T17:50:19+0800
     * @param                               Yaf\Dispatcher $dispatcher [description]
     * @return                              [type]                     [description]
     */
    public function _initRoute(Yaf\Dispatcher $dispatcher) {

        $config = new Yaf\Config\Ini(APPLICATION_PATH . '/config/Mroutes.ini');
        $dispatcher->getRouter()->addConfig($config);
    }
    

    /**
     * [_initDefaultDb 初始化数据库连接]
     * @author         Shaowei Pu <pushaowei@sporte.cn>
     * @CreateTime    2016-12-22T17:50:37+0800
     * @param                               Yaf\Dispatcher $dispatcher [description]
     * @return                              [type]                     [description]
     */
    public function _initDefaultDb(Yaf\Dispatcher $dispatcher)
    {
       Yaf\Loader::import('library/vendor/autoload.php');
       $database = YaConf::get('Mdatabases.db'); 

       // Eloquent ORM               
       $capsule = new Manager;
       $capsule->addConnection($database);          // 创建连接
       $capsule->setAsGlobal();                     // 设定全局静态访问
       $capsule->bootEloquent();                    // 启动Eloquet
    }
}
