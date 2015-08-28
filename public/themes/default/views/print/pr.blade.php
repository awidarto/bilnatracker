@extends('layout.brochure')

@section('content')
<html>
<body>
{{ HTML::style('bootstrap/css/bootstrap.min.css') }}
{{ HTML::style('bootstrap/css/bootstrap-responsive.min.css') }}
{{ HTML::style('bootstrap/css/app.css') }}
<style type="text/css">

    body {
        background-color: transparent;
        background: none;
        font-family: Arial,Helvetica, sans-serif;
    }


    h1{
        font-size: 28px;
        text-align: center;
        border-bottom: thin solid #ccc;
        padding-bottom: 15px;

    }

    h1,h2,h3,h4,h5,h6{
        font-family: Arial,Helvetica, sans-serif;
    }

    .h4{
        font-size: 16px;
        font-weight: bold;
    }

    .h5{
        font-size: 14px;
        font-weight: bold;
    }

    .h6{
        font-size: 13px;
        font-weight: bold;
    }

    .table th, .table td {
        /*padding: 8px;
        text-align: left;
        vertical-align: top;*/
        line-height: 16px;
        border-top: 1px solid #FFF;
    }

    table{
        width:100%;
        font-size: 12px;
        border:none;
    }

    table tr{
        border:none;
    }

    table td{
        /*min-width: 80px;*/
        border-color: transparent;
    }

    table th{
        font-weight: bold;
        background-color: #eee;
        border-color: transparent;
    }

    table th.h4{
        background-color: transparent;
    }

    .btn-buy{
        font-size: 36px;
    }

    i.fa fa-download , i.fa fa-map-marker, i.fa fa-envelope, i.fa fa-print{
        font-size: 18px;
    }

    ul.thumbnails_grid{
        list-style: none;
        margin-left: 0px;
    }

    ul.thumbnails_grid li{
        float:left;
    }

    ul.thumbnails_grid li a, #main-img {
        display: block;
        padding: 4px;
        margin:4px;
        border:thin solid #eef;
    }

    ul.thumbnails_grid li a img{
        width:120px;
        height:auto;
    }

    #map-container{
        display: inline-block;
        padding: 4px;
        border: solid thin #eef;
    }

    #map-box{
        margin-top: 15px;
        border-top: thin solid #eef;
        padding: 10px 4px;
    }

    table#finance tr td:last-child,
    table#finance tr td:last-child input[type=text]
    {
        text-align: right;
        font-size: 13px;
    }

    span.more{
        cursor: pointer;
        text-decoration: underline;
    }

    table td.curr{
        text-align: right;
    }

</style>

            <?php
                $address = $prop['number'].' '.$prop['address'].' '.$prop['city'].' '.$prop['state'].' '.$prop['zipCode'];
            ?>

    <div class="container" style="width:90%;">
        <div class="row-fluid">
            <div class="span3">
                <a href="{{ URL::to('/')}}" >{{ HTML::image('images/ialogo.png','Investors Alliance',array('class'=>'img-responsive' ) ) }}</a>
            </div>
            <div class="col-md-9">
            </div>
        </div>
        <h1 style="padding-left:0px;">Purchase Receipt</h1>
        <table style="width:100%;">
            <tbody>
                <tr>
                    <td style="vertical-align:top;width:45%;">
                        <h3>Investors Alliance</h3>
                        <h5>
                            125 East Main Street #350<br />American Fork, UT 84003<br />USA
                            <br />+1 614 432 8875
                        </h5>
                    </td>
                    <td>
                        <h3>Purchaser</h3>
                        <h4>{{ $trx['firstname'].' '.$trx['lastname']}}</h4>
                        @if($trx['company'] != '')
                            <h5>{{ $trx['company']}}</h5>
                        @endif
                        <h5>
                            {{ $trx['address'] }}<br />
                            {{ $trx['city']}} {{ $trx['state']}} {{ (isset($trx['zipCode']))?$trx['zipCode']:''}}<br />
                            {{ $trx['countryOfOrigin']}}
                        </h5>
                        <h5><b>Agent :</b> {{ $trx['agentName']}}</h5>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered" style="width:100%;">
            <thead>
                <tr>
                    <th colspan="4">Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Property ID</th>
                    <td>{{ $prop['propertyId']}}</td>
                    <th>Address</th>
                    <td>{{ $address}}</td>
                </tr>
                <tr>
                    <th>Lease Start Date</th>
                    <td>{{ $prop['leaseStartDate']}}</td>
                    <th>Lease Terms</th>
                    <td>{{ $prop['leaseTerms']}} month(s)</td>
                </tr>
                <tr>
                    <th>Monthly Rent Amount</th>
                    <td>${{ number_format($prop['monthlyRental'],0)}}</td>
                    <th>Section 8 Lease</th>
                    <td>{{ $prop['section8']}}</td>
                </tr>
                <tr>
                    <th>Purchased with</th>
                    <td class="h4">{{ $trx['fundingMethod']}}</td>
                    <th>Property Manager</th>
                    <td>{{$prop['propertyManager']}}</td>
                </tr>
            </tbody>
        </table>
        <?php
            $prop['tax'] = str_replace(array(',','.'), '', $prop['tax']);
        ?>
        <table class="table table-bordered table-striped" id="finance" >
            <thead>
                <tr>
                    <th>Price</th>
                    <th style="text-align:center;" class="col-md-2">Amount</th>
                    <th style="text-align:center;" class="col-md-2">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sale Price</td>
                    <td class="curr">${{ number_format($prop['listingPrice'],0,'.',',')}}</td>
                    <td>${{ number_format($prop['listingPrice'],0,'.',',')}}</td>
                </tr>
                <tr>
                    <td>{{ $trx['adjustmentType1'] }}</td>
                    <td class="curr">${{ number_format($trx['adjustment1'],0,'.',',')}}</td>
                    <td>${{ number_format($prop['listingPrice'] + $trx['adjustment1'],0,'.',',')}}</td>
                </tr>
                <tr>
                    <td>{{ $trx['adjustmentType1'] }}</td>
                    <td class="curr">${{ number_format($trx['adjustment2'],0,'.',',')}}</td>
                    <td>${{ number_format($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'],0,'.',',')}}</td>
                </tr>
                <tr>
                    <td>Annual Insurance Premium</td>
                    <td class="curr">${{ number_format($prop['insurance'],0,'.',',')}}</td>
                    <td>${{ number_format($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'],0,'.',',')}}</td>

                </tr>
                <tr>
                    <td>Tax Adjustment</td>
                    <td class="curr">${{ number_format($prop['tax'],0,'.',',')}}</td>
                    <td>${{ number_format($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'],0,'.',',')}}</td>
                </tr>
                <tr>
                    <td>Closing Cost</td>
                    <td class="curr">${{ number_format($trx['closingCost'],0,'.',',')}}</td>
                    <td>${{ number_format($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost'],0,'.',',')}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="curr"><h4>Total Purchase Price</h4></td>
                    <td><h4 id="total_purchase">
                        ${{ number_format($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost'],0,'.',',')}}
                        </h4>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered table-striped" id="finance" >
            <thead>
                <tr>
                    <th colspan="2">Cash and Earnest Money</th>
                    <th style="text-align:center;" class="col-md-2">Amount</th>
                    <th style="text-align:center;" class="col-md-2">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Cash Proceeds</td>
                    <td class="curr">${{ number_format( ($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost']),0,'.',',')}}</td>
                    <td>${{ number_format( ($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost']) ,0,'.',',')}}</td>
                </tr>
                <tr>
                    <td>Earnest Money Deposit 1</td>
                    <td class="curr">{{ $trx['earnestMoneyType1']}}</td>
                    <td class="curr">${{ number_format($trx['earnestMoney1'],0,'.',',')}}</td>
                    <td>${{ number_format( ($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost']) - $trx['earnestMoney1'] ,0,'.',',')}}</td>
                </tr>
                <tr>
                    <td>Earnest Money Deposit 2</td>
                    <td class="curr">{{ $trx['earnestMoneyType2']}}</td>
                    <td class="curr">${{ number_format($trx['earnestMoney2'],0,'.',',')}}</td>
                    <td>${{ number_format( ($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost']) - ($trx['earnestMoney1'] + $trx['earnestMoney2']) ,0,'.',',')}}</td>
                </tr>
                <tr>
                    <td>Loan</td>
                    <td class="curr">{{ ($trx['loanProceedPct'] == 0)?'':$trx['loanProceedPct'].'%' }}</td>
                    <td class="curr">${{ number_format($trx['loanProceed'],0,'.',',')}}</td>
                    <td>${{ number_format(($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost']) - ($trx['earnestMoney1'] + $trx['earnestMoney2'] + $trx['loanProceed']),0,'.',',') }}</td>
                </tr>

                <tr>
                    <td colspan="3" class="curr"><h4>Remaining Balance Due</h4></td>
                    <td><h4>
                        ${{ number_format(($prop['listingPrice'] + $trx['adjustment1'] + $trx['adjustment2'] + $prop['insurance'] + $prop['tax'] + $trx['closingCost']) - ($trx['earnestMoney1'] + $trx['earnestMoney2'] + $trx['loanProceed']),0,'.',',') }}
                        </h4>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="curr"><h4>Due On</h4></td>
                    <td ><h4>
                            {{ Carbon::parse($trx['createdDate'])->addDays(14)->format('d M Y')}}
                        </h4>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>


</body>
</html>
@stop