<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class AlumniPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $datas = array();

        $postTypes = [];

        $postOpportunityTypes = [];

        $reactionsTypes = [];

        $media = [null,'default.png'];

        foreach (\App\Util\Constant::ALUMNI_POST_TYPES_LIST as $key => $val){ $postTypes[] = $key; }
        foreach (\App\Util\Constant::POST_OPPORTUNITY_TYPE_LIST as $key => $val){ $postOpportunityTypes[] = $key; }
        foreach (\App\Util\Constant::POST_REACTION_LIST as $key => $val){ $reactionsTypes[] = $key; }

        for ($i=1; $i <= 20; $i++) {
            $postType = $postTypes[mt_rand(0,(count($postTypes)-1))];
            $data['alumni_id'] = mt_rand(1,2);
            $data['types'] = $postType;

            if($postType == \App\Util\Constant::ALUMNI_POST_TYPES_GENERAL){
                $data['content'] = $faker->paragraph(2);
            }else{
                $data['content'] = json_encode([
                    'title'=> $faker->sentence,
                    'company'=> $faker->company,
                    'location'=> $faker->country,
                    'types'=> $postOpportunityTypes[mt_rand(0,(count($postOpportunityTypes)-1))],
                    'description'=> $faker->paragraph(2)
                ]);
            }

            $data['media'] = $media[mt_rand(0,(count($media)-1))];
            $data['likes'] = mt_rand(0,20);
            $data['comments'] = mt_rand(0,20);
            $dates = \Carbon\Carbon::now()->subDay(mt_rand(0,30));
            $data['created_at'] = $dates;
            $data['updated_at'] = $dates;

            array_push($datas, $data);
        }

        DB::table('alumni_posts')->insert($datas);

        $posts = \App\AlumniPost::all();

        $likes = [];
        for ($i=1; $i <= 100; $i++) {
            $like['alumni_post_id'] = mt_rand(1,20);
            foreach ($posts as $post){
                if($like['alumni_post_id'] == $post->id){
                    $like['alumni_id'] = $post->alumni_id;
                    $like['alumni_like_id'] = mt_rand(1,2);
                }
            }
            $dates = \Carbon\Carbon::now()->subDay(mt_rand(0,30));
            $like['created_at'] = $dates;
            $like['updated_at'] = $dates;
            array_push($likes, $like);
        }

        DB::table('alumni_post_likes')->insert($likes);

        $comments = [];
        for ($i=1; $i <= 100; $i++) {
            $comment['alumni_post_id'] = mt_rand(1,20);
            foreach ($posts as $post){
                if($comment['alumni_post_id'] == $post->id){
                    $comment['alumni_id'] = $post->alumni_id;
                    $comment['alumni_comment_id'] = mt_rand(1,2);
                }
            }
            $comment['content'] = $faker->sentence;
            $dates = \Carbon\Carbon::now()->subDay(mt_rand(0,30));
            $comment['created_at'] = $dates;
            $comment['updated_at'] = $dates;
            array_push($comments, $comment);
        }

        DB::table('alumni_post_comments')->insert($comments);

        $reactions = [];
        for ($i=1; $i <= 100; $i++) {
            $reaction['alumni_post_id'] = mt_rand(1,20);
            foreach ($posts as $post){
                if($reaction['alumni_post_id'] == $post->id){
                    $reaction['alumni_id'] = $post->alumni_id;
                    $reaction['alumni_reaction_id'] = mt_rand(1,2);
                }
            }
            $reaction['reaction'] = $reactionsTypes[mt_rand(0,(count($reactionsTypes)-1))];
            $dates = \Carbon\Carbon::now()->subDay(mt_rand(0,30));
            $reaction['created_at'] = $dates;
            $reaction['updated_at'] = $dates;
            array_push($reactions, $reaction);
        }

        DB::table('alumni_post_reactions')->insert($reactions);
    }
}
