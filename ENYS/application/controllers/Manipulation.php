<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Manipulation extends CI_Controller {

    // Load Library.
    function __construct() {
        parent::__construct();
        $this->load->model('file_model');
        $this->load->library('image_lib');
        $this->image_lib->rotate();
        $this->image_lib->resize();
        $this->image_lib->crop();
        $this->image_lib->watermark();
    }

    // View "manipulation" Page.
    public function index() {
        $this->load->view('template/header'); 
        $this->load->view('manipulation');
        $this->load->view('template/footer');
    }

    // Perform manipuation on image ("crop","resize","rotate","watermark".)
    public function value() {
        if ($this->input->post("submit")) {
            // Use "upload" library to select image, and image will store in root directory "uploads" folder.
            $config = array(
            'upload_path' => "./uploads/",
            'upload_url' => base_url() . "uploads/",
            'allowed_types' => "gif|jpg|png|jpeg|pdf"
            );
            $this->load->library('upload', $config);
            if ($this->upload->do_upload()) {
                //If image upload in folder, set also this value in "$image_data".
                $image_data = $this->upload->data();
            }

            switch ($this->input->post("mode")) {
                case "crop":
                    //"$image_data" contains information about upload image, so this array pass in function for manipulation.
                    $data = $this->crop($image_data);
                    $this->load->view('template/header');
                    $this->load->view('manipulation', $data);
                    $this->load->view('template/footer');
                    break;
                case "resize":
                    //"$image_data" contains information about upload image, so this array pass in function for manipulation.
                    $data = $this->resize($image_data);
                    $this->load->view('template/header');
                    $this->load->view('manipulation', $data);
                    $this->load->view('template/footer');
                    break;
                case "rotate":
                    //"$image_data" contains information about upload image, so this array pass in function for manipulation.
                    $data = $this->rotate($image_data);
                    $this->load->view('template/header');
                    $this->load->view('manipulation', $data);
                    $this->load->view('template/footer');
                    break;
                case "watermark":
                    //"$image_data" contains information about upload image, so this array pass in function for manipulation.
                    $data = $this->water_marking($image_data);
                    $this->load->view('template/header');
                    $this->load->view('manipulation', $data);
                    $this->load->view('template/footer');
                    break;
                default:
                    // If select no option in above given, then this will alert you message.
                    echo "<script type='text/javascript'> alert('Please Select any option which you want to operate'); </script>";
                    $this->load->view('template/header');
                    $this->load->view('manipulation');
                    $this->load->view('template/footer');
                    break;
            }
        }
    }

    // Resize Manipulation.
    public function resize($image_data) {
        $img = substr($image_data['full_path'], 51);
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['create_thumb'] = TRUE;
        $config['new_image'] = './uploads/new_' . $img;
        $config['width'] = $this->input->post('width');
        $config['height'] = $this->input->post('height');

        //send config array to image_lib's  initialize function
        $this->image_lib->initialize($config);
        $src = $config['new_image'];
        $data['new_image'] = substr($src, 2);
        $data['img_src'] = base_url() . $data['new_image'];
        // Call resize function in image library.
        $this->image_lib->resize();
        // Return new image contains above properties and also store in "upload" folder.
        return $data;
    }

    // Rotate Manipulation.
    public function rotate($image_data) {
        $img = substr($image_data['full_path'], 51);
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['rotation_angle'] = $this->input->post('degree');
        $config['quality'] = "90%";
        $config['new_image'] = './uploads/rot_' . $img;

        //send config array to image_lib's  initialize function
        $this->image_lib->initialize($config);
        $src = $config['new_image'];
        $data['rot_image'] = substr($src, 2);
        $data['rot_image'] = base_url() . $data['rot_image'];
        // Call rotate function in image library.
        $this->image_lib->rotate();
        // Return new image contains above properties and also store in "upload" folder.
        return $data;
    }

    // Water Mark Manipulation.
    public function water_marking($image_data) {
        $img = substr($image_data['full_path'], 51);
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['wm_text'] = $this->input->post('text');
        $config['wm_type'] = 'text';
        $config['wm_font_path'] = './system/fonts/texb.ttf';
        $config['wm_font_size'] = '50';
        $config['wm_font_color'] = '#707A7C';
        $config['wm_hor_alignment'] = 'center';
        $config['new_image'] = './uploads/watermark_' . $img;

        //send config array to image_lib's  initialize function
        $this->image_lib->initialize($config);
        $src = $config['new_image'];
        $data['watermark_image'] = substr($src, 2);
        $data['watermark_image'] = base_url() . $data['watermark_image'];
        // Call watermark function in image library.
        $this->image_lib->watermark();
        // Return new image contains above properties and also store in "upload" folder.
        return $data;
    }

    // Crop Manipulation.
    public function crop($image_data) {
        $img = substr($image_data['full_path'], 51);
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['x_axis'] = $this->input->post('x1');
        $config['y_axis'] = $this->input->post('y1');
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $this->input->post('width_cor');
        $config['height'] = $this->input->post('height_cor');
        $config['new_image'] = './uploads/crop_' . $img;

        //send config array to image_lib's  initialize function
        $this->image_lib->initialize($config);
        $src = $config['new_image'];
        $data['crop_image'] = substr($src, 2);
        $data['crop_image'] = base_url() . $data['crop_image'];
        // Call crop function in image library.
        $this->image_lib->crop();
        // Return new image contains above properties and also store in "upload" folder.
        return $data;
    }
}
?>