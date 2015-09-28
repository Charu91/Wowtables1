<?php namespace WowTables\Http\Models\Eloquent\Reservations;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Image;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Queue\Queue;
use WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeListInteger;
use WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeListFloat;
use WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeListText;
use WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeDateTime;
use WowTables\Http\Models\Eloquent\Reservations\ReservationAttributes;
use WowTables\Http\Models\Eloquent\Reservations\ReservationStatus;
use WowTables\Http\Models\Eloquent\Reservations\ReservStatusLog;
use WowTables\Http\Models\Eloquent\Reservations\Logs\ReservationAttributeListTextLog;
use WowTables\Http\Models\Eloquent\Reservations\Logs\ReservationAttributeListFloatLog;
use WowTables\Http\Models\Eloquent\Reservations\Logs\ReservationAttributeListIntegerLog;
use WowTables\Http\Models\Eloquent\Reservations\Logs\ReservationAttributeDateTimeLog;
use WowTables\Http\Models\Eloquent\User;


class ReservationDetails extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reservation_details';

    protected $attributes = [];

    public function user()
    {
        return $this->belongsTo('WowTables\UserMeta');
    }

    public function experience()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Products\Product','product_id','id');
    }

    public function vendor_location()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation','vendor_location_id','id');
    }

    public function vendor_location_contacts(){
        return $this->hasOne('WowTables\VendorLocationContacts','vendor_location_id','vendor_location_id');
    }

    public function attributesText()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeListText', 'reservation_id', 'id');
    }

    public function attributesFloat()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeListFloat', 'reservation_id', 'id');
    }

    public function attributesInteger()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeListInteger', 'reservation_id', 'id');
    }

    public function attributesDatetime()
    {
        return $this->hasOne('WowTables\Http\Models\Eloquent\Reservations\ReservationAttributeDateTime', 'reservation_id', 'id');
    }

    public function reservationStatus()
    {
        return $this->hasManyThrough('WowTables\Http\Models\Eloquent\Reservations\ReservationStatus', 'WowTables\Http\Models\Eloquent\Reservations\ReservStatusLog','reservation_id','id');
    }

    public function scopeClosed($query)
    {

        return $query->whereIn('id', function($q1){
            $q1->select('reservation_id')
                ->from('reservation_status_log')
                ->leftJoin('reservation_statuses', 'reservation_status_log.new_reservation_status_id', '=', 'reservation_statuses.id')
                ->whereIn('reservation_statuses.status',['\'Closed\''])
                ->whereIn('reservation_status_log.new_reservation_status_id',function($query2) {
                    $query2->select(DB::raw('max(new_reservation_status_id) from reservation_status_log group by reservation_id'));
                });

        });
    }

    public function scopeToday($query){}

    public function scopeTest($query)
    {

        return $query->where('id',1);
    }

    public function updateAttributes($reservation_id, $data)
    {
        $attributeAlias = array_keys(isset($data['attributes']) ? $data['attributes'] : array());
        //print_r($attributeAlias);
        if(($key = array_search('date', $attributeAlias)) !== false) {
            unset($attributeAlias[$key]);
            $attributeAlias[] = 'reserv_datetime';
            $data['attributes']['reserv_datetime'] = Carbon::createFromFormat('d/m/Y H:i:s',$data['attributes']['date'].' '.$data['attributes']['time'])->toDateTimeString();
        }
        if(($key = array_search('time', $attributeAlias)) !== false) {
            unset($attributeAlias[$key]);
        }


        if(!empty($attributeAlias)) {
            $attributeAliasId = ReservationAttributes::whereIn('alias', $attributeAlias)->get(array('id', 'type', 'alias'));
            //print_r($attributeAliasId);die;
            foreach ($attributeAliasId as $singleAttribute) {
                $attrType = trim($singleAttribute->type);

                switch ($attrType) {
                    case 'text':
                        $attrText = ReservationAttributeListText::where('reservation_id', '=', $reservation_id)
                            ->where('reservation_attribute_id', '=', $singleAttribute->id)->get();
                        //print_r($attrText);die;
                        //var_dump($attrText->isEmpty());die;
                        if (!$attrText->isEmpty()) {
                            $value = $data['attributes'][$singleAttribute->alias];
                            //print_r($value);die;
                            if (!empty($value)) {

                                if(strcmp($value,$attrText[0]->attribute_value) != 0){
                                    //entering updated value log tables
                                    //echo "yo!";die;
                                    $textLog = new ReservationAttributeListTextLog();
                                    $textLog->reservation_status_log_id = self::getLastStatusLogId($reservation_id);
                                    $textLog->reservation_attribute_id = $singleAttribute->id;
                                    $textLog->old_attribute_value = $attrText[0]->attribute_value;
                                    $textLog->new_attribute_value = $value;
                                    $textLog->save();

                                    $attrText[0]->attribute_value = $value;
                                    $attrText[0]->save();
                                }

                            } else {
                                $attrText[0]->delete();
                            }

                        } else {

                            $value = $data['attributes'][$singleAttribute->alias];

                            if (isset($value) && $value != "") {
                                $insertText = new ReservationAttributeListText();
                                $insertText->reservation_id = $reservation_id;
                                $insertText->reservation_attribute_id = $singleAttribute->id;
                                $insertText->attribute_value = $value;
                                $insertText->save();
                            }

                        }

                        break;
                    case 'float':
                        $attrFloat = ReservationAttributeListFloat::where('reservation_id', '=', $reservation_id)
                            ->where('reservation_attribute_id', '=', $singleAttribute->id)->get();

                        if (!$attrFloat->isEmpty()) {

                            $value = $data['attributes'][$singleAttribute->alias];
                            if (!empty($value)) {

                                if(strcmp("".$value,"".$attrFloat[0]->attribute_value) != 0){
                                    //entering updated value log tables
                                    $floatLog = new ReservationAttributeListFloatLog();
                                    $floatLog->reservation_status_log_id = self::getLastStatusLogId($reservation_id);
                                    $floatLog->reservation_attribute_id = $singleAttribute->id;
                                    $floatLog->old_attribute_value = $attrFloat[0]->attribute_value;
                                    $floatLog->new_attribute_value = $value;
                                    $floatLog->save();

                                    $attrFloat[0]->attribute_value = $value;
                                    $attrFloat[0]->save();
                                }

                            } else {
                                $attrFloat[0]->delete();
                            }
                        } else {
                            $value = $data['attributes'][$singleAttribute->alias];

                            if (!empty($value)) {
                                $insertFloat = new ReservationAttributeListFloat();
                                $insertFloat->reservation_id = $reservation_id;
                                $insertFloat->reservation_attribute_id = $singleAttribute->id;
                                $insertFloat->attribute_value = $value;
                                $insertFloat->save();
                            }
                        }
                        break;
                    case 'integer':
                        $attrInteger = ReservationAttributeListInteger::where('reservation_id', '=', $reservation_id)
                            ->where('reservation_attribute_id', '=', $singleAttribute->id)->get();
                        if (!$attrInteger->isEmpty()) {
                            $value = $data['attributes'][$singleAttribute->alias];
                            if (!empty($value)) {

                                if($value != $attrInteger[0]->attribute_value){
                                    //entering updated value log tables
                                    $integerLog = new ReservationAttributeListIntegerLog();
                                    $integerLog->reservation_status_log_id = self::getLastStatusLogId($reservation_id);
                                    $integerLog->reservation_attribute_id = $singleAttribute->id;
                                    $integerLog->old_attribute_value = $attrInteger[0]->attribute_value;
                                    $integerLog->new_attribute_value = $value;
                                    $integerLog->save();

                                    $attrInteger[0]->attribute_value = $value;
                                    $attrInteger[0]->save();
                                }

                            } else {
                                $attrInteger[0]->delete();
                            }
                        } else {

                            $value = $data['attributes'][$singleAttribute->alias];
                            //print_r("Integer--".$value);
                            if (!empty($value)) {
                                $insertInteger = new ReservationAttributeListInteger();
                                $insertInteger->reservation_id = $reservation_id;
                                $insertInteger->reservation_attribute_id = $singleAttribute->id;
                                $insertInteger->attribute_value = $value;
                                $insertInteger->save();
                            }
                        }
                        break;
                    case 'datetime':
                        $attrDateTime = ReservationAttributeDateTime::where('reservation_id', '=', $reservation_id)
                            ->where('reservation_attribute_id', '=', $singleAttribute->id)->get();
                        if (!$attrDateTime->isEmpty()) {
                            $value = $data['attributes'][$singleAttribute->alias];
                            if (!empty($value)) {

                                if(strtotime($attrDateTime[0]->attribute_value) != strtotime($value)){
                                    //entering updated value log tables
                                    $datetimeLog = new ReservationAttributeDateTimeLog();
                                    $datetimeLog->reservation_status_log_id = self::getLastStatusLogId($reservation_id);
                                    $datetimeLog->reservation_attribute_id = $singleAttribute->id;
                                    $datetimeLog->old_attribute_value = $attrDateTime[0]->attribute_value;
                                    $datetimeLog->new_attribute_value = $value;
                                    $datetimeLog->save();


                                    $attrDateTime[0]->attribute_value = $value;
                                    $attrDateTime[0]->save();
                                }
                            } else {
                                $attrDateTime[0]->delete();
                            }
                        } else {

                            $value = $data['attributes'][$singleAttribute->alias];
                            if (!empty($value)) {
                                $insertDatetime = new ReservationAttributeDateTime();
                                $insertDatetime->reservation_id = $reservation_id;
                                $insertDatetime->reservation_attribute_id = $singleAttribute->id;
                                $insertDatetime->attribute_value = $value;
                                $insertDatetime->save();
                            }
                        }
                }
            }
        }

        //logic for addons save
        if(isset($data['attributes']['actual_addon_takers'])){
            $response = $this->addActualAddonTakers($reservation_id,$data['attributes']['actual_addon_takers']);
        }

        if(isset($data['userdetails'])){
            /*if(isset($data['attributes']['addon'])){
                $response = $this->addActualAddonTakers($data);
                //echo $response;die;
            }*/
            $reservationStatus = $this->changeReservationStatus($reservation_id,$data['userdetails']);
            //echo $reservationStatus;die;
        }

        return ['status' => 'success'];
    }

    public function getByReservationId($id){

        $reservationDetailsWithAttr = ReservationDetails::with('attributesText','attributesInteger','attributesFloat','attributesDatetime')->findOrFail($id);

        //print_r($reservationDetailsWithAttr);die;

        $this->populateVendorAttributes($reservationDetailsWithAttr->attributesInteger);
        $this->populateVendorAttributes($reservationDetailsWithAttr->attributesFloat);
        $this->populateVendorAttributes($reservationDetailsWithAttr->attributesText);
        //$this->populateVendorAttributes($reservationDetailsWithAttr->attributesDatetime);

        //for datetime type because one to one relationship
        $resrvDateTime = $reservationDetailsWithAttr->attributesDatetime;
        $this->attributes[$resrvDateTime->attributesAlias->alias] = $resrvDateTime->attribute_value;
        return [ 'ReservationDetails' => ReservationDetails::find($id),'attributes' => $this->attributes];
    }

    public function populateVendorAttributes ($reservDetailsAttributes)
    {
        //print_r($reservDetailsAttributes);

        foreach($reservDetailsAttributes as $attribute)
        {
            $name  = $attribute->attributesAlias->alias;
            $value = $attribute->attribute_value;
            $this->attributes[$name] = $value;
        }

    }

    public function changeReservationStatus($reservation_id,$data){

        $userModel = User::find($data['user_id']);
        //$reservStatus = new ReservationStatus();
        $statusId = $data['status'];
        //$reservStatus->save();
        if(!empty($data['addons'])){
            //print_r($data['addons']);die;
            foreach($data['addons'] as $prod_id => $count){
                $result = ReservAddonVarientDetails::where('options_id',$prod_id)->where('reservation_id',$reservation_id)->first();
                $result->reservation_status_id = $statusId;
                $result->save();
                //print_r($result);die;
            }
        }

        $statusLog = ReservStatusLog::where('reservation_id','=',$reservation_id)->orderBy('id','desc')->take(1)->get();
        if($statusLog->isEmpty()){

            $statusLogEntry = new ReservStatusLog();
            $statusLogEntry->reservation_id = $reservation_id;
            $statusLogEntry->user_id = $userModel->id;
            $statusLogEntry->old_reservation_status_id = 0;
            $statusLogEntry->new_reservation_status_id = $statusId;
            $statusLogEntry->save();

        } else {

            $statusLogEntry = new ReservStatusLog();
            $statusLogEntry->reservation_id = $reservation_id;
            $statusLogEntry->user_id = $userModel->id;
            $statusLogEntry->old_reservation_status_id = $statusLog[0]->new_reservation_status_id;
            $statusLogEntry->new_reservation_status_id = $statusId;
            $statusLogEntry->save();

        }

        return "Success";
    }

    public function getReservationStatus($reserv_ids,$statuses)
    {

        $reservStatus = ReservStatusLog::leftjoin('reservation_statuses', 'reservation_status_log.new_reservation_status_id', '=', 'reservation_statuses.id')
            ->whereIn('reservation_statuses.id', $statuses)
            ->whereIn('reservation_status_log.created_at', function ($query) use ($reserv_ids) {
                $query->select(DB::raw('max(created_at)'))
                    ->from('reservation_status_log')
                    ->whereIn('reservation_id', $reserv_ids)
                    ->groupBy('reservation_id');
            })
            ->select(DB::raw('reservation_statuses.status,reservation_id'))->get();

        $reservStatusArr = array();
        foreach($reservStatus as $rs){
            $reservStatusArr[$rs->reservation_id] = $rs->status;
        }
        //print_r($reservStatusArr);die;
        return $reservStatusArr;
    }

    protected function getLastStatusLogId($reservId){

        $statusLogId = DB::select(DB::raw('SELECT max(id) as statusId FROM reservation_status_log where reservation_id = '.$reservId.' group by reservation_id'));
        //print_r($statusLogId[0]->statusId);die;
        return $statusLogId[0]->statusId;

    }

    protected function addActualAddonTakers($reservation_id,$addonInfo){

        //print_r($addonInfo);die;
        $statusCancelledNew = DB::select(DB::raw('select * from reservation_status_log having reservation_id = '.$reservation_id.' and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
        $reservationIdArr = array();
        foreach($statusCancelledNew as $reservId){
            $statusId = $reservId->new_reservation_status_id;
        }

        foreach($addonInfo as $prod_id => $count){


                $result = ReservAddonVarientDetails::where('options_id',$prod_id)->where('reservation_id',$reservation_id)->first();
                $result->reservation_status_id = $statusId;
                $result->save();


        }

    }

}


