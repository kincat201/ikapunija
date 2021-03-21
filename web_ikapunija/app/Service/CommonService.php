<?php

namespace App\Service;

use App\UnitKerjaGroup;
use App\Util\Constant;

class CommonService {

    public static function GenerateDefaultLimit($request) {
        $data = [];
        $data['limit'] = !empty($request->dataPerPage) ? $request->dataPerPage : 10;
        $data['offset'] = !empty($request->pageCurrent) ? ($data['limit'] * ($request->pageCurrent - 1)) : 0;
        return $data;
    }

    public static function GenerateDefaultOrder($request) {
        $data = [];
        $data['name'] = !empty($request->orderBy) && count($request->orderBy) > 0 ? $request->orderBy[0] : 'id';
        $data['sort'] = !empty($request->orderBy) && count($request->orderBy) > 0 ? $request->orderBy[1] : 'desc';
        return $data;
    }

    public static function GenerateDefaultOption($request) {
        $data = [];
        $generateLimit = self::GenerateDefaultLimit($request);
        $generateOrder = self::GenerateDefaultOrder($request);

        $data['limit'] = $generateLimit['limit'];
        $data['offset'] = $generateLimit['offset'];
        $data['order'] = $generateOrder;
        return $data;
    }

    public static function GenerateListModel($model, $option, $custom = false) {
        $data = [];

        $data['total'] = $custom ? count($model->get()) : $model->count();
        $data['list'] = $model->limit($option['limit'])
                ->offset($option['offset'])
                ->orderBy($option['order']['name'], $option['order']['sort'])
                ->get();
        return $data;
    }

    public static function FormatDate($datetime, $full = false) {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'min',
            's' => 'sec',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) : 'just now';
    }

    public static function ConvertToSecondsTime($time, $format = '%02d:%02d:%02d') {
        if ($time < 1) {
            return '00:00:00';
        }
        $hours = floor($time / 3600);
        $minutes = ($time / 60 % 60);
        $seconds = ($time % 60);
        return sprintf($format, $hours, $minutes, $seconds);
    }

    public static function ConvertToYearsMonths($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return '-';
        }
        $year = floor($time / 12);
        $month = ($time % 12);
        return sprintf($format, $year, $month);
    }

    public static function GetUnitKerja($types = '', $parent_id = '') {
        $unitKerjaGroup = UnitKerjaGroup::with(['unit_kerja_2.unit_kerja_3.unit_kerja_4'])->orderBy('group_order', 'asc')->get();
        $result = [];
        $datas = [];
        foreach ($unitKerjaGroup as $kerjaGroup) {
            foreach ($kerjaGroup->unit_kerja_2 as $unit_kerja_2) {
                $uk2 = new \stdClass();
                $uk2->unit_kerja_2_id = $unit_kerja_2->unit_kerja_2_id;
                $uk2->singkatan = $unit_kerja_2->singkatan;
                $uk2->unit_kerja3 = [];

                foreach ($unit_kerja_2->unit_kerja_3 as $unit_kerja_3) {
                    $uk3 = new \stdClass();
                    $uk3->unit_kerja_3_id = $unit_kerja_3->unit_kerja_3_id;
                    $uk3->singkatan = $unit_kerja_3->singkatan;
                    $uk3->unit_kerja4 = [];

                    foreach ($unit_kerja_3->unit_kerja_4 as $unit_kerja_4) {
                        $uk4 = new \stdClass();
                        $uk4->unit_kerja_4_id = $unit_kerja_4->unit_kerja_4_id;
                        $uk4->singkatan = $unit_kerja_4->singkatan;
                        $uk3->unit_kerja4[] = $uk4;
                    }
                    $uk2->unit_kerja3[] = $uk3;
                }
                $datas[] = $uk2;
            }
        }

        if ($types == Constant::UNIT_KERJA2) {
            foreach ($datas as $data2) {
                $uk2 = new \stdClass();
                $uk2->unit_kerja_2_id = $data2->unit_kerja_2_id;
                $uk2->singkatan = $data2->singkatan;
                $result[] = $uk2;
            }
        } elseif ($types == Constant::UNIT_KERJA3) {
            foreach ($datas as $data2) {
                if ($data2->unit_kerja_2_id == $parent_id) {
                    foreach ($data2->unit_kerja3 as $data3) {
                        $uk3 = new \stdClass();
                        $uk3->unit_kerja_3_id = $data3->unit_kerja_3_id;
                        $uk3->singkatan = $data3->singkatan;
                        $result[] = $uk3;
                    }
                }
            }
        } elseif ($types == Constant::UNIT_KERJA4) {
            foreach ($datas as $data2) {
                foreach ($data2->unit_kerja3 as $data3) {
                    if ($data3->unit_kerja_3_id == $parent_id) {
                        foreach ($data3->unit_kerja4 as $data4) {
                            $uk4 = new \stdClass();
                            $uk4->unit_kerja_4_id = $data4->unit_kerja_4_id;
                            $uk4->singkatan = $data4->singkatan;
                            $result[] = $uk4;
                        }
                    }
                }
            }
        } else {
            $result = $datas;
        }
        return $result;
    }

    public static function ConverTanggalToDDMMYYYY($data = "") {
        $result = "";
        if (!empty($data)) {
            $result = substr($data, -2) . "-" . substr($data, 5, 2) . "-" . substr($data, 0, 4);
        }
        return $result;
    }

}
