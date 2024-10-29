<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = [
            ['name' => 'المنيا'],
            ['name' => 'ملوي'],
            ['name' => 'سمالوط'],
            ['name' => 'بني مزار'],
            ['name' => 'مغاغة'],
            ['name' => 'أبو قرقاص'],
            ['name' => 'دير مواس'],
            ['name' => 'مطاي'],
            ['name' => 'العدوة'],
            ['name' => 'تلة'],
            ['name' => 'شوشة'],
            ['name' => 'طوخ الخيل'],
            ['name' => 'بني محمد سلطان'],
            ['name' => 'نزلة حسين'],
            ['name' => 'صفط الخمار'],
            ['name' => 'زاوية سلطان'],
            ['name' => 'دير جبل الطير'],
            ['name' => 'طهنا الجبل'],
            ['name' => 'عزبة أبو قرقاص البلد'],
            ['name' => 'قصر هور'],
            ['name' => 'كفر المغاربة'],
            ['name' => 'كوم المحرص'],
            ['name' => 'البيهو'],
            ['name' => 'نزلة أسمنت'],
            ['name' => 'نزلة رمضان'],
            ['name' => 'عزبة جريس'],
            ['name' => 'عزبة زكريا'],
            ['name' => 'كفر درويش'],
            ['name' => 'الشيخ فضل'],
            ['name' => 'بني عبيد'],
            ['name' => 'بني علي'],
            ['name' => 'دير السنقورية'],
            ['name' => 'شلقام'],
            ['name' => 'طحا الأعمدة'],
            ['name' => 'قلوصنا'],
            ['name' => 'نزلة حرز'],
            // Add more small villages as needed
        ];

        DB::table('villages')->insert($villages);
    }
}
