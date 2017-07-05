<?php
namespace App\Controller\Admin;

use App\BaseController\AdminBaseController as Base;

/**
 * 系统菜单管理
 * @package App\Controller\Admin
 */
class Sysmenu extends Base
{
    public function __construct($swoole)
    {
        parent::__construct($swoole);
        $this->addBreadcrumb('系统管理','/admin/System/index');
    }

    /**
     * 菜单管理界面
     */
    public function index()
    {
        $this->setSeoTitle('菜单管理');
        $this->addBreadcrumb('菜单管理',$this->currentUrl);
        //菜单模块类别
        $sysMenu = new \App\Service\SysMenu();
        $this->assign('moduleTypeList', $sysMenu->getModuleTypeList());
        $moduleType = isset($_GET['moduleType']) && $_GET['moduleType'] ? $_GET['moduleType'] : $sysMenu::MENU_TYPE_ADMIN;
        $this->assign('moduleType', $moduleType);
        //菜单模型
        $sysMenuModel = model('SysMenu');
        $menuList = $sysMenuModel->getMenuList($moduleType);
        //树结构菜单列表
        $tree = new \App\Common\Tree('menuId', 'parentMenuId', 'child');
        $tree->nameKey = 'menuName';
        $tree->load($menuList);
        $optionHtml = $tree->buildOptions();
        //菜单选择列表
        $this->assign('menuTreeOption', $optionHtml);
        //可嵌套列表
        $nestableHtml = $tree->buildNestableTree();
        $this->assign('nestableHtml', $nestableHtml);
        $this->display();
    }

    /**
     * 保存菜单数据
     */
    public function add()
    {

    }

    /**
     * 保存排序层级数据
     */
    public function saveSort()
    {
        try{
            $sortData = $this->request->post['sortData'];
            if (empty($sortData)){
                throw new \Exception('排序数据有误');
            }
            $sysMenu = new \App\Service\SysMenu();
            $rs = $sysMenu->saveSort($sortData);
            if ($rs){
                return $this->showMsg('success', '保存排序成功');
            }else{
                throw new \Exception('保存排序失败');
            }
        }catch (\Exception $e){
            return $this->showMsg('error', $e->getMessage());
        }
    }
}