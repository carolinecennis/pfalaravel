@extends('layouts.app')

@section('title', 'Run')

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
@section('js')
@section('content')


<div class="section">
    <h1>
        @if (isset($count))
        {!! $count !!} records analyzed
        @else
        Run Program
        @endif </h1>
    <div class="wrapper">

        {{  Form::open(array('action'=>'DuplicatesController@processDuplicates', 'method' => 'post')) }}
        <table>
            <tr>
                <th><label for="division">Division</label></th>
                <td>
                    @if (isset($divisions))
                    {!! Form::select('division', $divisions, null, array('id' => 'division')) !!}
                    @else
                    {{ Form::text('division', strtoupper($division)) }}
                    @endif
                </td>
            </tr>
            <tr>
                <th><label for="beginrunnumber">Beginning Run Number</label></th>
                <td>
                    @if (isset($beginrunnumber))
                    {{ Form::text('beginrunnumber', $beginrunnumber) }}
                    @else
                    <select name="beginrunnumber" id="beginrunnumber" name="beginrunnumber"></select>
                    @endif
                </td>
            </tr>
            <tr>
                <th><label for="date">Date</label></th>
                <td>
                    @if (isset($date))
                    {{ Form::text('date', $date) }}
                    @else
                    <select name="date" id="date" name="date"></select>
                    @endif
                </td>
            </tr>
            <th><label for="checkBoxA">Shipper City</label></th>
                <td>
                    {{ Form::checkbox('shipperCity',null,null, array('id'=>'shipperCity')) }}
                </td>
            <tr>
                <th><label for="checkBoxB">Run Number</label></th>
                <td>
                    {{ Form::checkbox('runNumber',null,null, array('id'=>'runNumber')) }}
                </td>
        </table>
        {!! Form::submit('Run', array('class' => 'btn btn-success')) !!}
    </div>
</div>
@endsection
