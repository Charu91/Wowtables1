<?php namespace WowTables\Http\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use WowTables\Http\Models\Frontend\RegpageJournal;
use WowTables\Http\Models\Frontend\RegpageTestimorials;

class RegpageModel extends Model {
	protected $table = 'regpage_images';
    protected $fillable = [];

    protected $hidden = [];

    function add_bgimages($data)
    {

        $data = array(
            'path' => $data['path'],
            'full_path' => $data['full_path'],
            'description'=> $data['description'],
            'title'=> $data['title'],  
        );

        Self::create($data);
    }
    function get_images($id=false)
    {
        if($id)
        {
            $query = Self::join('media_resized_new as media','regpage_images.media_id','=','media.media_id')
            		->where('media.image_type', 'listing')
                    ->where('id', $id)
            		->get();

            $result = $query->toArray();
        }
        else
        {
            $query = Self::join('media_resized_new as media','regpage_images.media_id','=','media.media_id')
            		->where('media.image_type', 'listing')
                    ->get();

            $result = $query->toArray();
        }
                 
        return $result;
    }
    function delete_image($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->delete('regpage_bgimages'); 
        
        return $query;
    }    
    function get_journal()
    {
        
        $query = RegpageJournal::all();   
        return $query->toArray();
    }
    function add_journal($data)
    {
       $query = $this->db->update('regpage_journal', $data); 
       return $query;
    } 
    
    function get_testimonials($where = array())
    {
        if(!empty($where))
            $this->db->where($where);
        $query = RegpageTestimorials::all();   
        return $query->toArray();
    }
    
    function add_edit_testimonials($id=null,$data = array())
    {   
	 	$data = array('source_title'=>addslashes($data['source_title']),
	 			      'someone_famous_in'=> addslashes($data['someone_famous_in']),
	 			      'content' => addslashes($data['content'])
					  );
        if(is_numeric($id)){
            $this->db->where(array('id'=>$id));
            $this->db->update('regpage_testimonials', $data); 
        }else {
            $this->db->insert('regpage_testimonials', $data);  
        }
    } 
    
    function delete_testimonials($id=null){
        if(is_numeric($id)){
            $this->db->where('id', $id);
            $this->db->delete('regpage_testimonials'); 
        }
    }  
}