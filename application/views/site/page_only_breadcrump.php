<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section id="breadcrump_path">
     <div class="breadcrump">	
            
     <?php 
        if($this->uri->total_rsegments() > 2) {
        	echo basic_get_breadcrumppath($this->uri->segment_array(), $this->uri->rsegment_array(), base_url());
        }
     ?>

    </div>
</section>
<section id="content">