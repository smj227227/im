<?php

$uuidArr = [5,8];
arsort($uuidArr);
$uuid = implode('',$uuidArr);
print_r($uuid);