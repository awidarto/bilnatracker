@extends('layout.brochure')

@section('content')
<html>
<body>
    <style type="text/css">

        body{
            font-family: Helvetica, sans-serif;
            font-size: 12px;
            margin:0px;
            padding: 0px;
        }

        #head,#imghead{
            width:100%;
            margin-top: 10px;
        }


        .sub img, #imghead img{
            width: 100%;
            height:auto;
        }

        .sub{
            width:33%;
            float:left;
            display: inline-block;
        }

        #subimage{
            margin-top: 10px;
        }

        #footer{
            height:30px;
            position: absolute;
            bottom: 0px;
            display: block;
            width: 100%;
            padding: 10px;
            border-top: thin solid #ccc;
        }

        h1,h2{
            font-weight: normal;
            padding: 0px;
        }

        h1{
            font-size: 22px;
            line-height: 22px;
        }

        h2{
            font-size: 22px;
            line-height: 24x;
        }

        #title{
            background-color: orange;
            margin-top: 5px;
            padding: 5px;
            padding-left: 10px;
            width: 50%;
            float:left;
            display: inline-block;
        }

        #amenity{
            margin-top: 15px;
            font-size: 20px;
            font-family: Arial,sans-serif;
            font-style: normal;
            width: 48%;
            display: inline-block;
            text-align: center;
        }

        .clear{
            clear: both;
        }

        th,td{
            vertical-align: top;
        }

        table.contact{
            margin-left: 15px;
        }

        table.feature{
            width: 100%;
            text-align: center;
            margin-top: 5px;
        }

        table.feature th.item{
            width: 30%;
            height: 35px;
            font-size: 18px;
            line-height: 20px;
            background-color: orange;
            vertical-align: middle;
        }

        table.feature td{
            vertical-align: middle;
            font-size: 24px;
            border: thin solid orange;
        }


        table.financial th.item{
            width: 50%;
            font-size: 14px;
            line-height: 20px;
            vertical-align: middle;
        }

        table.financial td{
            vertical-align: middle;
            font-size: 14px;
        }

        #overviews{

        }

        .overview{
            width: 50%;
            font-size: 14px;
            display: inline-block;
            float: left;
            vertical-align: top;
            background-color: yellow;
        }

        #overviewtable th, #overviewtable td,{
            vertical-align: top;
        }

        #tabhead h1{
            text-align: left;
        }

        table.financial th.item{
            width: 50%;
            vertical-align: middle;
            text-align: left;
            padding-right: 8px;
        }

        .title-span{
            display: block;
            font-size: 24px;
            font-family: Helvetica,sans-serif;
        }

    </style>
    <div id="head">
        <div class="sub">
            {{ HTML::image('images/ialogo.png')}}
        </div>
            {{--
        <div class="sub">
            <table class="contact">
                <tbody>
                <tr>
                    <th class="address">USA:</th>
                    <td>125 East Main Street #350<br>American Fork, UT 84003<br>USA
                    </td>
                </tr>
                <tr>
                    <th class="phone">Phone:</th>
                    <td>+1 614 432 8875</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="sub">
            <table class="contact">
                <tbody>
                <tr>
                    <th class="address">Australia:</th>
                    <td>PO BOX 6175<br>Linden Park, SA 5065<br>Australia
                    </td>
                </tr>
                <tr>
                    <th class="phone">Phone:</th>
                    <td>+61 458 159 5065</td>
                </tr>
                </tbody>
            </table>

        </div>
            --}}
    </div>
    <div id="imghead">
        <div>
            {{ HTML::image($prop['defaultpictures']['brchead'])}}
        </div>
    </div>
    <div id="subimage">
        <div class="sub">
            {{ HTML::image($prop['defaultpictures']['brc1'])}}
        </div>
        <div class="sub">
            {{ HTML::image($prop['defaultpictures']['brc2'])}}
        </div>
        <div class="sub">
            {{ HTML::image($prop['defaultpictures']['brc3'])}}
        </div>
    </div>
    <div class="clear"></div>

    <table id="overviewtable" style="width:100%;padding:0px;margin-top:10px;">
        <tr>
            <td style="background-color:orange;padding:8px;width:50%;" >
                <span class="title-span">{{$prop['number'].' '.$prop['address']}}</span>
                <span class="title-span">{{$prop['city'].' '.$prop['state'].' '.$prop['zipCode']}}</span>
            </td>
            <td style="padding:0px;">
                <table class="feature" style="margin:0px;" >
                    <tbody>
                        <tr>
                            <th class="item">Bed</th>
                            <th class="item">Bath</th>
                        </tr>
                        <tr>
                            <td>{{ $prop['bed']}}</td>
                            <td>{{ $prop['bath']}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="font-size:12px;">
                <table class="financial" style="margin:0px;width:100%;" >
                    <tbody>
                        <tr>
                            <th class="item" style="width:50%" >Type</th>
                            <td>{{ $prop['type']}}</td>
                        </tr>
                        <tr>
                            <th class="item">Year Built</th>
                            <td>{{ $prop['yearBuilt']}}</td>
                        </tr>
                        <tr>
                            <th class="item">Size</th>
                            <td>{{ number_format($prop['houseSize'],0) }} sqft.</td>
                        </tr>
                        <tr>
                            <th class="item">Lot Size</th>
                            <td>
                                @if( $prop['lotSize'] < 100)
                                    {{ number_format($prop['lotSize'] * 43560,0) }} sqft
                                @else
                                    {{ $prop['lotSize'] }} sqft
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="item">Category</th>
                            <td>{{ ucwords( strtolower($prop['category'] ) ) }}</td>
                        </tr>
                        <tr>
                            <th class="item">Property Management</th>
                            <td>{{ ucwords( strtolower($prop['propertyManager'] ) ) }}</td>
                        </tr>
                        <tr>
                            <th colspan="2" class="item">Description</th>
                        </tr>
                        <tr>
                            <td colspan="2">{{ $prop['description']}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="font-size:12px;">
                <table class="financial" style="margin:0px;width:100%;" >
                    <tbody>
                        <tr>
                            <th class="item">Price</th>
                            <td>{{ Ks::usd($prop['listingPrice']) }}</td>
                        </tr>
                        <tr>
                            <th class="item">FMV</th>
                            <td>{{ Ks::usd($prop['FMV'])}}</td>
                        </tr>
                        <tr>
                            <th class="item">Monthly Rent</th>
                            <td>{{ Ks::usd($prop['monthlyRental'])}}</td>
                        </tr>
                        <tr>
                            <th class="item">Tax</th>
                            <td>{{ Ks::usd($prop['tax'])}}</td>
                        </tr>
                        <tr>
                            <th class="item">Rental Yield</th>
                            <td>{{ number_format((($prop['monthlyRental']*12)/$prop['listingPrice'])*100,1)}}%</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php

                                    $address = $prop['number'].' '.$prop['address'].' '.$prop['city'].' '.$prop['state'].' '.$prop['zipCode'];
                                    if($prop['type'] == 'LAND'){
                                        $color = 'green';
                                        $label = 'L';
                                    }else{
                                        $color = 'blue';
                                        $label = 'H';
                                    }
                                    $address_url = urlencode($address);
                                ?>
                                <img src="http://maps.googleapis.com/maps/api/staticmap?center={{ $address_url }}&zoom=13&size=325x175&maptype=roadmap&markers=color:{{ $color }}%7Clabel:{{ $label }}%7C{{ $address_url }}&sensor=false" style="float:left"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="clear"></div>
    <div id="footer">
        &copy; 2013 Investors Alliance
    </div>
</body>
</html>
@stop