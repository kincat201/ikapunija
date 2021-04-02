<?php

namespace App\Util;

class Constant {

    const COMMON_YES = 'Y';
    const COMMON_NO = 'N';

    const COMMON_YESNO_LIST = [
        self::COMMON_YES => 'YES',
        self::COMMON_NO => 'NO',
    ];

    const USER_LOG_ADMIN_MODE = 'admin';
    const USER_LOG_USER_MODE = 'user';

    const ACTIVE_STATUS_YES = 'Y';
    const ACTIVE_STATUS_NO = 'N';
    const ACTIVE_STATUS_VERIFICATION = 'V';
    const ACTIVE_STATUS_FORGOT = 'B';
    const ACTIVE_STATUS_PENDING = 'P';

    const ACTIVE_STATUS_LIST = [
        self::ACTIVE_STATUS_YES => 'YES',
        self::ACTIVE_STATUS_NO => 'NO',
        self::ACTIVE_STATUS_VERIFICATION => 'VERIFICATION',
        self::ACTIVE_STATUS_FORGOT => 'FORGOT PASSWORD',
        self::ACTIVE_STATUS_PENDING => 'PENDING',
    ];

    const LAST_EDUCATION_D1 = 'D1';
    const LAST_EDUCATION_D2 = 'D2';
    const LAST_EDUCATION_D3 = 'D3';
    const LAST_EDUCATION_D4 = 'D4';
    const LAST_EDUCATION_S1 = 'S1';
    const LAST_EDUCATION_S2 = 'S2';
    const LAST_EDUCATION_S3 = 'S3';

    const LAST_EDUCATION_LIST = [
        self::LAST_EDUCATION_D1 => 'Diploma I',
        self::LAST_EDUCATION_D2 => 'Diploma II',
        self::LAST_EDUCATION_D3 => 'Diploma III',
        self::LAST_EDUCATION_D4 => 'Diploma IV',
        self::LAST_EDUCATION_S1 => 'Sarjana',
        self::LAST_EDUCATION_S2 => 'Magister',
        self::LAST_EDUCATION_S3 => 'Doktor',
    ];

    const ALUMNI_POST_TYPES_GENERAL = 'GENERAL';
    const ALUMNI_POST_TYPES_OPPORTUNITY = 'OPPORTUNITY';

    const ALUMNI_POST_TYPES_LIST = [
        self::ALUMNI_POST_TYPES_GENERAL => 'General',
        self::ALUMNI_POST_TYPES_OPPORTUNITY => 'Opportunity',
    ];

    const ALUMNI_POST_MEDIA_TYPES_PHOTO = 'PHOTO';
    const ALUMNI_POST_MEDIA_TYPES_VIDEO = 'VIDEO';

    const ALUMNI_POST_MEDIA_TYPES_LIST = [
        self::ALUMNI_POST_MEDIA_TYPES_PHOTO => 'Photo',
        self::ALUMNI_POST_MEDIA_TYPES_VIDEO => 'Video',
    ];

    const POST_OPPORTUNITY_TYPE_FULL_TIME = 'FULL_TIME';
    const POST_OPPORTUNITY_TYPE_PART_TIME = 'PART_TIME';
    const POST_OPPORTUNITY_TYPE_FREELANCE = 'FREELANCE';

    const POST_OPPORTUNITY_TYPE_LIST = [
        self::POST_OPPORTUNITY_TYPE_FULL_TIME => 'Full Time',
        self::POST_OPPORTUNITY_TYPE_PART_TIME => 'Part Time',
        self::POST_OPPORTUNITY_TYPE_FREELANCE => 'Freelance',
    ];

    const POST_REACTION_LIKE = 'LIKE';
    const POST_REACTION_LOVE = 'LOVE';
    const POST_REACTION_CURIOUS = 'CURIOUS';

    const POST_REACTION_LIST = [
        self::POST_REACTION_LIKE => 'Like',
        self::POST_REACTION_LOVE => 'Love',
        self::POST_REACTION_CURIOUS => 'Curious',
    ];

    const SEARCH_ALUMNI_TYPES_LOCATION = 'LOCATION';
    const SEARCH_ALUMNI_TYPES_COMPANY = 'COMPANY';

    const SEARCH_ALUMNI_TYPES_LIST = [
        self::SEARCH_ALUMNI_TYPES_LOCATION => 'Location',
        self::SEARCH_ALUMNI_TYPES_COMPANY => 'Companies',
    ];

    const NOTIFICATION_STATUS_READ = 'READ';
    const NOTIFICATION_STATUS_UNREAD = 'UNREAD';

    const NOTIFICATION_STATUS = [
        self::NOTIFICATION_STATUS_READ => 'Terbaca',
        self::NOTIFICATION_STATUS_UNREAD => 'Belum Dibaca',
    ];

    const NOTIFICATION_TYPE_GENERAL = 'GENERAL';
    const NOTIFICATION_TYPE_ALUMNI_POST_LIKE = 'ALUMNI_POST_LIKE';
    const NOTIFICATION_TYPE_ALUMNI_POST_INTERACTION = 'ALUMNI_POST_INTERACTION';
    const NOTIFICATION_TYPE_ALUMNI_POST_COMMENT = 'ALUMNI_POST_COMMENT';
    const NOTIFICATION_TYPE_ALUMNI_POST_OPPORTUNITY = 'ALUMNI_POST_OPPORTUNITY';

    const NOTIFICATION_TYPE_ALUMNI_LIST = [
        self::NOTIFICATION_TYPE_GENERAL => 'Umum',
        self::NOTIFICATION_TYPE_ALUMNI_POST_LIKE => 'Post di like',
        self::NOTIFICATION_TYPE_ALUMNI_POST_INTERACTION => 'Post di tanggapi',
        self::NOTIFICATION_TYPE_ALUMNI_POST_COMMENT => 'Post di komentari',
        self::NOTIFICATION_TYPE_ALUMNI_POST_OPPORTUNITY => 'Lowongan Kerja',
    ];

    const NOTIFICATION_TYPE_ALUMNI_SUBJECT_LIST = [
        self::NOTIFICATION_TYPE_GENERAL => 'Informasi Umum',
        self::NOTIFICATION_TYPE_ALUMNI_POST_LIKE => 'Postingan Mendapat Like',
        self::NOTIFICATION_TYPE_ALUMNI_POST_INTERACTION => 'Postingan Mendapat Reaksi',
        self::NOTIFICATION_TYPE_ALUMNI_POST_COMMENT => 'Balasan Komentar',
        self::NOTIFICATION_TYPE_ALUMNI_POST_OPPORTUNITY => 'Lowongan Pekerjaan',
    ];

    const NOTIFICATION_TYPE_ALUMNI_MESSAGE_LIST = [
        self::NOTIFICATION_TYPE_GENERAL => '',
        self::NOTIFICATION_TYPE_ALUMNI_POST_LIKE => 'Memberikan Like Postingamu',
        self::NOTIFICATION_TYPE_ALUMNI_POST_INTERACTION => 'Memberi Reaksi Postingamu',
        self::NOTIFICATION_TYPE_ALUMNI_POST_COMMENT => 'Mengomentari Postingamu',
        self::NOTIFICATION_TYPE_ALUMNI_POST_OPPORTUNITY => 'Memposting Lowongan Pekerjaan',
    ];
}
