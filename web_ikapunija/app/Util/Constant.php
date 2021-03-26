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

    const ALUMNI_POST_TYPES_GENERAL = 'GENERAL';
    const ALUMNI_POST_TYPES_OPPORTUNITY = 'OPPORTUNITY';

    const ALUMNI_POST_TYPES_LIST = [
        self::ALUMNI_POST_TYPES_GENERAL => 'General',
        self::ALUMNI_POST_TYPES_OPPORTUNITY => 'Opportunity',
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

}
