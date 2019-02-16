<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 2/15/19
 * Time: 5:57 PM
 */
require 'config/config.php';
require '../vendor/autoload.php';
use Tests\Models\Database;
//Initialize Illuminate Database Connection
new Database();

use Tests\Models\Post;

$q = new Question();
dd($q->all());
