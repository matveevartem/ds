<?php

use yii\grid\GridView;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Monitor</h1>

        <p class="lead">Here you can see results of request "SELECT * FROM {table}" for each tables.</p>

    </div>

    <div class="body-content">

        <div class="row">
            
            <div class="col-lg-4">
                <h2>Orders</h2>
                <p class="table-view">
                    <?= yii\grid\GridView::widget([
                        'dataProvider' => new yii\data\ArrayDataProvider([
                            'allModels' => $orders,
                            'pagination' => [
                                'pageSize' => 10,
                            ],
                            'sort' => [
                                'attributes' => ['order_id', 'created_at'],
                            ],
                        ]),
                        'columns' => [
                            'created_at',
                            'order_id',
                            'summ',
                            'commision',
                        ],                        
                    ]);?>
                </p>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-3">
                <h2>Transactions</h2>
                <p class="table-view">
                    <?= yii\grid\GridView::widget([
                        'dataProvider' => new yii\data\ArrayDataProvider([
                            'allModels' => $transactions,
                            'pagination' => [
                                'pageSize' => 10,
                            ],
                            'sort' => [
                                'attributes' => ['user_id', 'created_at'],
                            ],
                        ]),
                        'columns' => [
                            'created_at',
                            'user_id',
                            'summ',
                        ],
                    ]);?>
                </p>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-3">
                <h2>Totals</h2>
                <p class="table-view">
                    <?= yii\grid\GridView::widget([
                        'dataProvider' => new yii\data\ArrayDataProvider([
                            'allModels' => $totals,
                            'pagination' => [
                                'pageSize' => 10,
                            ],
                            'sort' => [
                                'attributes' => ['user_id', 'updated_at'],
                            ],
                        ]),
                        'columns' => [
                            'updated_at',
                            'user_id',
                            'summ',
                        ],
                    ]);?>
                </p>
            </div>

        </div>

    </div>
</div>
