<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$title = 'Market Hotpot';
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- BEGIN META -->
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            crm
        </title>
        <?= $this->Html->meta('appicon.png', '/img/appicon.png', ['type' => 'icon']); ?>
        <!-- END META -->

        <?= $this->Html->css('custom.css') ?>
        <!-- END STYLESHEETS -->
        <style type="text/css">
        html, 
        body {
            height: 100%;
            background: #fff;
        }
        .full-height{
            height: 100%;
        }
        .white-backgroud{
            background: #fff;
        }
        .login-view {
            margin-bottom: 40px;
        }
        @media (min-width:  992px){
            .mobile-img{
                display: none;
            }
        }
        @media (max-width: 991px){
            .desktop-img{
                display: none;
            }
        }
        @media (max-width: 1199px) and (min-width: 992px){
            body {
                background-color: lightgreen;
            }
        }
        div.message.error{display: none;}
        </style>
    </head>
    <body class="login">
        <?= $this->fetch('content') ?>
        <?= $this->Flash->render() ?>
        <!-- END JAVASCRIPT -->
    </body>
</html>
