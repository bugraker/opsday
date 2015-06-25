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
        function clickDay() {
            $('#day').hide();
            $('#since').show();
        }
        function clickSince() {
            $('#since').hide();
            $('#day').show();
        }
    </script>

        @if (!empty($day))
            <div class="alert alert-default fade in ops-coal">

                @if (!empty($op))
                    <h2 class="text-center op-lightgray">{{$op}}</h2>
                @else
                    <h3 class="text-center op-lightgray">OPSDAY</h3>
                @endif

                <div class="op-jumbotron op-logo">
                    <h1 class="op-center">

                        @if ($since >= 0)
                            <span id="day" class="op-label"  onclick="clickDay()">{{$day}}<span class="op-rollover">{{$rollover}}</span></span>
                        @else
                            <span id="day" class="op-label" onclick="clickDay()">-{{$day}}<span class="op-rollover">{{$rollover}}</span></span>
                        @endif

                        <span id="since" class="op-label" onclick="clickSince()">
                            {{$since}}
                        </span>
                    </h1>
                </div>

        @else

            <div class="alert alert-danger fade in" style="margin: 10px;">
                <h3 class="op-inline">OPs DAY ERROR&nbsp;</h3><i class="fa fa-info-circle op-super"></i>

        @endif

            @if (!empty($message))
                <h4>{{$message}}</h4>
                    <p>Start:&nbsp;&nbsp;{{$start}}&nbsp;&nbsp;&nbsp;
                        <a href="calendar">
                            <span class="fa fa-calendar" title="Change the Start day"></span>
                        </a>
                    </p>
                    <p>Day:&nbsp;&nbsp;{{$date}}</p>
            @else
                <div class="container">
                    <p class="op-lightgray">Start:&nbsp;&nbsp;{{$start}}&nbsp;&nbsp;&nbsp;
                        <a href="calendar">
                            <span class="fa fa-calendar" title="Change the Start day"></span>
                        </a>
                    </p>
                    <p class="op-lightgray">Date:&nbsp;&nbsp;{{$date}}</p>
                </div>
            @endif

            </div>

        @if ($display == "since")
            <script>
                $('#day').hide();
                $('#since').show();
            </script>
        @else
            <script>
                $('#day').show();
                $('#since').hide();
            </script>
        @endif

@stop