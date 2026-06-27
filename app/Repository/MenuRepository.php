<?php

namespace App\Repository;

use App\Models\Menus;
use App\Traits\IconComponent;

class  MenuRepository
{
    use IconComponent;
    public static function getAllSideBar($request)
    {
        $menus = Menus::withRecursiveExpression('recursive_menus', function ($query) {
            $query->select('id', 'name', 'sub_id', 'url', 'icon', 'order', 'type')
                ->from('menus')
                ->whereNull('sub_id')
                ->unionAll(
                    Menus::select('menus.id', 'menus.name', 'menus.sub_id', 'menus.url', 'menus.icon', 'menus.order', 'menus.type')
                        ->join('recursive_menus', 'menus.sub_id', '=', 'recursive_menus.id')
                );
        })
        ->from('recursive_menus')
        ->orderBy('order')
        ->get();
        // dd($menus->toArray());
        $menus=self::buildTreeCustom($menus);
        return $menus;
    }
    private static function buildTreeCustom($items, $parentId = null) {
        $branch = [];
        foreach ($items as $item) {
            if ($item->sub_id == $parentId) {
                $children = self::buildTreeCustom($items, $item->id);
                $node=Collect($item)->only(['id','name','sub_id','url','icon','type'])->toArray();
                // if($node['id']=='10'){
                //     dd($children,$item->id,$items->toArray());
                // }
                if($node['icon']){
                    $node['icon']=self::MenuList($node['icon'],'me-1');
                }
                if ($children) {
                    $node['data_sub'] = $children;
                }
                $node['class']='';
                if($node['url']){
                    $current=url()->current();
                    if(url()->current()==route('menus_catering.import')){
                        $current=route('menus_catering');
                    }
                    $node['class']=($current==route($node['url']))?'active':'';
                    $node['url']=route($node['url']);
                }
                $branch[] = $node;
            }
        }
        return $branch;
    }
}
