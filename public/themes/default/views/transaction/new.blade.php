@extends('layout.front')


@section('content')

<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

<div class="row-fluid">
    <div class="col-md-6">

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('number','Street Number')->class('col-md-12')->maxlength(6) }}
            </div>
            <div class="span8">
                {{ Former::text('address','Address')->class('col-md-12') }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('city','City')->class('col-md-12') }}
            </div>
            <div class="col-md-4">
                {{ Former::select('state')->options(Config::get('country.us_states'))->label('States') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('zipCode','ZIP')->class('col-md-12')->maxlength(5) }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::select('type')->options(Config::get('ia.type'))->label('Type') }}
            </div>
            <div class="col-md-6">
                {{ Former::text('yearBuilt','Year Built')->class('span8')->maxlength(4)  }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('FMV','FMV')->class('col-md-12') }}
            </div>
            <div class="span5">
                {{ Former::text('listingPrice','Listing Price')->class('span8') }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('bed','# of Bedroom')->class('span8') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('bath','# of Bathroom')->class('span8') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('garage','# of Garage')->class('span8') }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::select('basement')->options(Config::get('ia.boolean'))->label('Basement')->class('col-md-12') }}
            </div>
            <div class="col-md-4">
                {{ Former::select('pool')->options(Config::get('ia.boolean'))->label('Pool')->class('col-md-12') }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('houseSize','House Size (SqFt)')->class('col-md-12') }}
            </div>
            <div class="col-md-6">
                {{ Former::text('lotSize','Lot Size (SqFt)')->class('col-md-6') }}
            </div>
        </div>

        {{ Former::text('typeOfConstruction','Type of Construction')->class('span5') }}

        {{ Former::text('parcelNumber','Parcel Number')->class('span5') }}

        {{ Former::textarea('description','Property Description')->class('col-md-10 editor')->rows(8) }}

        {{ Former::text('tags','Tags')->class('tag_keyword') }}

        {{--
        <div class="control-group">
            <label for="locationPicker">Select Location</label>
            <div class="controls">
                <fieldset class="gllpLatlonPicker" id="locationPicker" >
                    <div class="gllpMap">Google Maps</div>
                    <div class="form-search" style="margin-top:10px;margin-bottom:10px;">
                        <input type="text" class="gllpSearchField input-xlarge search-query" placeholder="type address and click Search button">
                        <button type="button" class="gllpSearchButton btn">Search</button>
                    </div>

                    {{ Former::hidden('latitude')->class('gllpLatitude')}}
                    {{ Former::hidden('longitude')->class('gllpLongitude')}}
                    {{ Former::hidden('zoom')->class('gllpZoom')}}

                    {{--<input type="button" class="gllpUpdateButton" value="update map">--}}
                </fieldset>
            </div>

        </div>
        --}}


        {{ Form::submit('Save',array('name'=>'submit','class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

    </div>
    <div class="col-md-6">


        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::select('propertyStatus')->options(Config::get('ia.publishing'))->label('Status') }}
            </div>
            <div class="span5">
                {{ Former::select('category')->options(Config::get('ia.category'))->label('Category') }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('monthlyRental','Monthly Rental')->class('col-md-12') }}
            </div>
            <div class="span5">
                {{ Former::select('section8')->options(Config::get('ia.boolean'))->label('Section 8')->class('span7') }}
            </div>
        </div>


        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('leaseTerms','Lease Terms')->append('months')->class('col-md-6')->maxlength(2) }}
            </div>
            <div class="span5">
                {{ Former::text('leaseStartDate','Lease Start Date')->class('col-md-12 datepicker')
                    ->data_format('dd-mm-yyyy')
                    ->append('<i data-time-icon="fa fa-time" data-date-icon="fa fa-calendar"></i>') }}
            </div>
        </div>

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('HOA','HOA')->class('col-md-12') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('tax','Tax')->class('col-md-12') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('insurance','Insurance')->class('col-md-12') }}
            </div>
        </div>

        {{ Former::text('propertyManager','Property Manager') }}

        {{ Former::textarea('specialConditionRemarks','Special Condition Remarks')->class('col-md-10')->rows(8) }}

        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('imageupload')->title('Select Images')->label('Upload Images')->make() }}

    </div>
</div>

<div class="row-fluid pull-right">
    <div class="col-md-4">
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

});

</script>

@stop