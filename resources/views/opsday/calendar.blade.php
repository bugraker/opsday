@extends('layouts.default')
<!--
OPSDAY

The MIT License (MIT)

Copyright (c) 2015 George Patton Simcox, email: geo.simcox@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
-->

@section('content')
    <script>
        function resetop() {
            var date = new Date();
            yyyy = date.getFullYear();
            mm = date.getMonth() + 1;
            if (mm < 10) {
                mm = '0' + mm;
            }
            dd = date.getDate();
            if (dd < 10) {
                dd = '0' + dd;
            }
            $('#datepicker').val(yyyy + '-' + mm + '-' + dd);
            $('#start').val(yyyy + '-' + mm + '-' + dd);
        }
    </script>

    <div class="container-fluid op-margin">

        <h3 class="op-lightgray op-inline">Edit OPSDAY&nbsp;</h3>
        <i class="fa fa-info-circle op-super op-lightgray" title="Enter the OP Start date buy clicking on the date field, selecting the date, then clicking the Submit button.  To select a future Start date, please refer to the OPSDAY documentation."></i>

        <div class="container-fluid">
            <div class=" op-well-calendar op-coal">
                <table >
                    <tr>
                        <td class="op-td">
                            <i class="fa fa-trash op-primary" title="Reset day" onclick="resetop()"></i>
                            OP Start Date:
                            <input class="op-space op-inline" type="text" id="datepicker" size="10" value="{{$start}}" readonly>
                            {!! Form::open(array('method' => 'post', 'action' => array('OpsDayController@showDay'),'class' => 'op-inline')) !!}
                            <input id="start" type="hidden" name="start" value="{{$start}}">
                            <input id="target" type="hidden" name="target" value="{{$target}}">
                            <input id="set" type="hidden" name="set" value=true>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-td">
                            OP Name: <input id="op" class="op-space op-inline" type="textarea" name="op" value="{{$op}}" size="20">
                        </td>
                    </tr>
                    <tr>
                        <td class="op-td">Display Option:
                            <div class="container-fluid">
                        @if ($display == 'since')
                                <div class="op-inline"><input id="opsday" class="op-space op-inline" type="radio" name="display" value="opsday">&nbsp;&nbsp;OPs&nbsp;=>&nbsp;<span class="op-badge">AK</span>&nbsp;&nbsp;</div>
                                <div><input id="usesince" class="op-space op-inline" type="radio" name="display" value="since" checked="checked">&nbsp;&nbsp;Numeric&nbsp;=>&nbsp;<span class="op-badge">10</span></div>
                            @else
                                <div class="op-inline"><input id="opsday" class="op-space op-inline" type="radio" name="display" value="opsday" checked="checked">&nbsp;&nbsp;OPs&nbsp;=>&nbsp;<span class="op-badge">AK</span></div>
                                &nbsp;&nbsp;
                                <div><input id="usesince" class="op-space op-inline" type="radio" name="display" value="since">&nbsp;&nbsp;Numeric&nbsp;=>&nbsp;<span class="op-badge">10</span></div>
                            @endif
                            </div>
                        </td>
                    </tr>
                </table>
                    {!! Form::submit('Submit', array('class' => 'btn-xs btn-primary')) !!}
                    {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop