<?php

class ScannerController extends AdminController {

    public function __construct()
    {
        //parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        /*
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));
        */
        //$this->model = new Attendee();
        //$this->model = DB::collection('documents');

    }

    public function getIndex(){
        $this->title = 'Scan Code';
        return View::make('scan.barcode')->with('title',$this->title);
    }

    public function getStockinput(){
        $this->title = 'Stock Unit Input';
        return View::make('scan.barcode')->with('title',$this->title);
    }

    public function getStockcheck(){
        $this->title = 'Stock Unit Check';
        return View::make('scan.stockcheck')->with('title',$this->title);
    }

    public function getCashier(){
        $this->title = 'Cashier';
        return View::make('scan.barcode')->with('title',$this->title);
    }

}