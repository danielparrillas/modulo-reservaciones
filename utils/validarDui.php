<?php

function isDUI($dui)
{

  if ((bool)preg_match('/(^\d{8})-(\d$)/', $dui) === true) {
    [$digits, $digit_veri] = explode('-', $dui);
    $sum = 0;

    for ($i = 0, $l = strlen($digits); $i < $l; $i++) {
      $sum += (9 - $i) * (int)$digits[$i];
    }

    return (bool)((int)$digit_veri === (int) (10 - ($sum % 10)) % 10);
  }

  return false;
}
