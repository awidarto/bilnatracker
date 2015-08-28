<?php

class InpropController extends ImporterController{

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);

        $this->crumb->append(strtolower($this->controller_name));

        $this->title = 'Import Properties';

        $this->upload_dir = public_path().'/storage/xls';

        $this->input_name = 'xlsfile';

        $this->model = new Property();

    }



}