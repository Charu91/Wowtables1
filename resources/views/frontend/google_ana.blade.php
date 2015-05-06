<?php
use WowTables\Http\Models\Frontend\CodeModel;
    $CodeModel = new CodeModel();
    $codes = $CodeModel->getAll();
    
    foreach($codes as $code){
        if($code['view_pages'] == 'all' ){
            echo $code['code'];
        }
    }
?>


<?php /*if($this->uri->segment(1) == 'registration'): ?>
<!-- Start of GetKudos Script -->
<!--<script>
(function(w,t,gk,d,s,fs){if(w[gk])return;d=w.document;w[gk]=function(){
(w[gk]._=w[gk]._||[]).push(arguments)};s=d.createElement(t);s.async=!0;
s.src='//static.getkudos.me/widget.js';fs=d.getElementsByTagName(t)[0];
fs.parentNode.insertBefore(s,fs)})(window,'script','getkudos');

getkudos('create', 'gourmetitup');

</script>-->
<!-- End of GetKudos Script -->
<?php endif;*/ ?>