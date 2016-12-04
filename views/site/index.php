<?php
use app\models\Perms;
?>
<style>
     a.btn-main{background-color: #D75C37;color:#fff; width:120px;height: 120px;text-align: center;font-size: 16px;font-weight: bold;margin:10px;padding:10px;position: relative;display: inline-block;line-height: 2em;}
    a.btn-main:hover{width:130px;height:130px; transition: all 0.3s ease;background-color: #67727A;color:#C3D7DF;}
</style>
<div style="text-align: center;">
    <a href="<?= Yii::$app->urlManager->createUrl('products/index')?>" class="btn btn-info btn-main <?= Perms::getPerms("Products",1)?"":"hidden"?>" style="width:120px;height: 120px;"><span class="fa fa-4x fa-list"></span><br/><span style="font-weight: bolder;font-size: 18px;"><?= __("Products") ?></span><a>
    <a href="<?= Yii::$app->urlManager->createUrl('stocks/index')?>" class="btn btn-info btn-main <?= Perms::getPerms("Stocks",1)?"":"hidden"?>" style="width:120px;height: 120px;"><span class="fa fa-4x fa-truck"></span><br/><span style="font-weight: bolder;font-size: 18px;"><?= __("Stocks") ?></span><a>
</div>