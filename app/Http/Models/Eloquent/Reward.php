<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model {

    protected $table = 'rewards_details';

    protected $fillable = ['user_id','points_earned','points_redeemed','points_removed','description'];

    public function get_all_records($id){
        DB::table('rewards_details')->where('user_id', $id);
        return ['data' => new \stdClass()];
    }

}