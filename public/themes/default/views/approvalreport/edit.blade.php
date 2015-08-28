@extends('layout.front')


@section('content')
<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<h3>{{$title}} : {{ $formdata['propertyId'] }}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}


<div class="row-fluid">
    <div class="col-md-6">

        {{ Former::select('state')->options(Config::get('country.us_states'))->label('States') }}
        {{ Former::text('number','Street Number')->class('span3')->maxlength(6) }}
        {{ Former::text('address','Address') }}
        {{ Former::text('city','City') }}
        {{ Former::text('zipCode','ZIP')->class('col-md-2')->maxlength(5) }}


        <?php Former::framework('Nude');?>

        <div class="row-fluid lmargin">
            <div class="span3"></div>
            <div class="span3">
                {{ Former::select('type')->options(Config::get('ia.type'))->label('Type')->class('col-md-6') }}
            </div>
            <div class="span3">
                {{ Former::text('yearBuilt','Year Built')->class('col-md-6')->maxlength(4)  }}
            </div>
        </div>

        <div class="row-fluid lmargin">
            <div class="span3"></div>
            <div class="span3">
                {{ Former::text('FMV','FMV')->class('col-md-6') }}
            </div>
            <div class="span3">
                {{ Former::text('listingPrice','Listing Price')->class('col-md-6') }}
            </div>
        </div>

        <div class="row-fluid lmargin">
            <div class="span3"></div>
            <div class="span3">
                {{ Former::text('bed','# of Bedroom')->class('number-field') }}
            </div>
            <div class="span3">
                {{ Former::text('bath','# of Bathroom')->class('number-field') }}
            </div>
            <div class="span3">
                {{ Former::text('garage','# of Garage')->class('number-field') }}
            </div>
        </div>

        <div class="row-fluid lmargin">
            <div class="span3"></div>
            <div class="span3">
                {{ Former::select('basement')->options(Config::get('ia.boolean'))->label('Basement')->class('col-md-6') }}
            </div>
            <div class="span3">
                {{ Former::select('pool')->options(Config::get('ia.boolean'))->label('Pool')->class('col-md-6') }}
            </div>
        </div>

        <div class="row-fluid lmargin">
            <div class="span3"></div>
            <div class="col-md-4">
                {{ Former::text('houseSize','House Size (SqFt)')->class('col-md-6') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('lotSize','Lot Size (SqFt)')->class('col-md-6') }}
            </div>
        </div>


        <?php Former::framework('TwitterBootstrap');?>


        {{ Former::text('typeOfConstruction','Type of Construction')->class('span5') }}

        {{ Former::textarea('description','Property Description')->class('col-md-10 editor')->rows(8) }}

        {{ Former::text('tags','Tags')->class('tag_keyword') }}

            <fieldset class="gllpLatlonPicker" >

                <div class="gllpMap" style="margin-left:180px;" >Google Maps</div>
                <div class="form-search" style="margin-left:180px;margin-top:10px;margin-bottom:10px;">
                    <input type="text" class="gllpSearchField input-xlarge search-query" placeholder="type address and click Search button">
                    <button type="button" class="gllpSearchButton btn">Search</button>
                </div>

                {{ Former::hidden('latitude')->class('gllpLatitude')}}
                {{ Former::hidden('longitude')->class('gllpLongitude')}}
                {{ Former::hidden('zoom')->class('gllpZoom')}}
                {{--<input type="button" class="gllpUpdateButton" value="update map">--}}
            </fieldset>

    </div>
    <div class="col-md-6">

        {{ Former::select('propertyStatus')->options(Config::get('ia.publishing'))->label('Status') }}

        {{ Former::select('category')->options(Config::get('ia.category'))->label('Category') }}
        {{ Former::text('monthlyRental','Monthly Rental')->class('span3') }}
        {{ Former::select('section8')->options(Config::get('ia.boolean'))->label('Section 8')->class('span3') }}
        {{ Former::text('leaseTerms','Lease Terms')->append('months')->class('col-md-2')->maxlength(2) }}
        {{ Former::text('leaseStartDate','Lease Start Date')->class('span7 datepicker')
            ->data_format('dd-mm-yyyy')
            ->append('<i data-time-icon="fa fa-time" data-date-icon="fa fa-calendar"></i>') }}

        <?php Former::framework('Nude');?>

        <div class="row-fluid lmargin">
            <div class="span3"></div>
            <div class="col-md-2">
                {{ Former::text('tax','Tax')->class('col-md-6') }}
            </div>
            <div class="span3">
                {{ Former::text('insurance','Insurance')->class('col-md-6') }}
            </div>
            <div class="span3">
                {{ Former::text('HOA','HOA')->class('col-md-6') }}
            </div>
        </div>

        <?php Former::framework('TwitterBootstrap');?>

        {{ Former::text('propertyManager','Property Manager') }}

        {{ Former::textarea('specialConditionRemarks','Special Condition Remarks')->class('col-md-10')->rows(8) }}

        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('imageupload')->title('Select Images')->label('Upload Images')->make($formdata) }}

    </div>
</div>

<div class="row-fluid pull-right">
    <div class="col-md-4">
        {{ Form::submit('Save',array('name'=>'submit','class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
    </div>
</div>
{{Former::close()}}

{{ HTML::style('css/jquery-gmaps-latlon-picker.css')}}

{{ HTML::script('js/jquery-gmaps-latlon-picker.js')}}


<script type="text/javascript">


$(document).ready(function() {

    $('select').select2({
      width : 'copy'
    });

    $('#field_role').change(function(){
        //alert($('#field_role').val());
        // load default permission here
    });

    /*
    var editor = new wysihtml5.Editor('ecofriendly', { // id of textarea element
      toolbar:      'wysihtml5-toolbar', // id of toolbar element
      parserRules:  wysihtml5ParserRules // defined in parser rules set
    });
    */

    $('#name').keyup(function(){
        var title = $('#name').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });


});

</script>

@stop