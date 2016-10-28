@extends('layouts.app')

@section('title', 'Reports')

<style>
    .wrapper {
        width: 500px;
        margin: 0 auto;
    }
    .section.page p {width: 475px; margin-left: auto; margin-right: auto;}
    .section.page .media-details h1 .price {color: #0000A0; padding-right: 10px; font-size: 34px;}
    .section.catalog h2 {
        font-size: 24px;
        text-align: center;
        line-height: 1.6;
        font-weight: normal;
        padding-top: 20px;
    }
    form[name= "border"] {width: 475px;
        margin: 50px auto;
        border : 1px solid #000;
        padding : 10px;
        padding-top: 5%}
    form tr, tr {text-align:left;vertical-align: top; padding:2px;}
    form table {width: 475px; margin-bottom: 30px; border: 1px solid #a5a5a5;
    }
    form th {
        width: 150px;
        vertical-align: middle;
        padding: 8px;
    }
    form td, select {
        padding: 15px 15px;
    }
    form td select,
    table td input,
    table td textarea {
        width: 100%;
        border-radius: 4px;
        padding: 10px;
        font-size: 14px;
        font-family: 'Open Sans', sans-serif;
    }
    form input[type="submit"] {
        width: 475px;
        text-align: center;
        border: 0;
        background: #DC143C;
        color: #FFF;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        font-size: 16px;
        padding: 14px 0 16px;
        font-family: 'Open Sans', sans-serif;
    }

    select {
        width: 50%;
        text-align: left;
        border-radius: 4px;
        padding: 10px;
        font-size: 16px;
        font-family: 'Open Sans', sans-serif;
    }
</style>

@section('content')

@section('js')

@endsection

<div class="section">
    <h1>Get Reports</h1>
    <div class="wrapper">

        {{ Form::open(array('action'=>'ReportController@downloadReports', 'method' => 'post'), array('id' => 'form')) }}
         <table>
                <tr>
                    <th><label for "reports">Report Types</th>
                    <td>
                        {!! Form::select('reportType', $reportTypes, null, array('id' => 'reportType')) !!}
                    </td>
                </tr>
                <tr>
                    <th><label for "division">Division</th>
                    <td>
                        {!! Form::select('division', $divisions, null, array('id' => 'division')) !!}
                    </td>
                </tr>
                <tr>
                    <th><label for="date">Date</label></th>
                    <td>
                        <select name="date" id="date"></select>
                    </td>
                </tr>
            </table>
        <input type="submit" value="Download Reports" name="retrieveType" >
        <p>
            <input type="submit" value="View Reports" name="retrieveType" >
        {{ Form::close() }}
        </p>
    </div>


</div>
@endsection