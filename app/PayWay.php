<?php
namespace App; use App\Library\Helper; use Illuminate\Database\Eloquent\Model; class PayWay extends Model { protected $guarded = array(); protected $casts = array('channels' => 'array'); const ENABLED_DISABLED = 0; const ENABLED_PC = 1; const ENABLED_MOBILE = 2; const ENABLED_ALL = 3; const TYPE_SHOP = 1; const TYPE_API = 2; public function getPayByWeight() { $spbefb16 = $spca31ef = 0; $sp193c43 = array(); $sp630e91 = array(); foreach ($this->channels as $spb8bcdd) { $sp630e91[] = intval($spb8bcdd[0]); } $sp26fae0 = \App\Pay::gets()->filter(function ($sp3c5b44) use($sp630e91) { return in_array($sp3c5b44->id, $sp630e91); }); $sp25e883 = array(); foreach ($sp26fae0 as $spe1d2b6) { $sp25e883[$spe1d2b6->id] = $spe1d2b6; } foreach ($this->channels as $spb8bcdd) { $sp602524 = intval($spb8bcdd[0]); $sp553b7e = intval($spb8bcdd[1]); if ($sp553b7e && isset($sp25e883[$sp602524]) && $sp25e883[$sp602524]->enabled > 0) { $spbefb16 += $sp553b7e; $sp854f89 = $spca31ef + $sp553b7e; $sp193c43[] = array('min' => $spca31ef, 'max' => $sp854f89, 'pay_id' => $sp602524); $spca31ef = $sp854f89; } } if ($spbefb16 <= 0) { return null; } $sp989471 = mt_rand(0, $spbefb16 - 1); foreach ($sp193c43 as $sp551b8d) { if ($sp551b8d['min'] <= $sp989471 && $sp989471 < $sp551b8d['max']) { return $sp25e883[$sp551b8d['pay_id']]; } } return null; } public static function gets($sp264a55, $spef50d2 = null) { $sped9569 = self::query(); if ($spef50d2 !== null) { $sped9569->where($spef50d2); } $spa4f8c7 = $sped9569->orderBy('sort')->get(array('name', 'img', 'channels')); $sp497584 = array(); foreach ($spa4f8c7 as $spd348b5) { $sp3c5b44 = $spd348b5->getPayByWeight(); if ($sp3c5b44) { $sp3c5b44->setAttribute('name', $spd348b5->name); $sp3c5b44->setAttribute('img', $spd348b5->img); $sp3c5b44->setVisible(array('id', 'name', 'img', 'fee')); $sp497584[] = $sp3c5b44; } } return $sp497584; } }