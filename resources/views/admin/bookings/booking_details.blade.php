@extends('templates.admin_layout')

@section('content')




    <style>
        /* NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! */
        /* -------------------------------------
   * For horizontal version, set the
   * $vertical variable to false
   * ------------------------------------- */
        /* -------------------------------------
         * General Style
         * ------------------------------------- */
        /*@import url(http://fonts.googleapis.com/css?family=Noto+Sans);
        body {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
            font-size: 100%;
            font-family: "Noto Sans", sans-serif;
            color: #272727;
            background: #fff;
        }*/

        h2 {
            margin: 3em 0 0 0;
            font-size: 1.5em;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* -------------------------------------
         * timeline
         * ------------------------------------- */
        #timeline {
            list-style: none;
            margin: 50px 0 30px 120px;
            padding-left: 30px;
            border-left: 8px solid #eee9dc;
        }
        #timeline li {
            margin: 40px 0;
            position: relative;
        }
        #timeline p {
            margin: 0 0 15px;
        }

        .date {
            margin-top: -10px;
            top: 50%;
            left: -158px;
            font-size: 0.95em;
            line-height: 20px;
            position: absolute;
        }

        .circle {
            margin-top: -10px;
            top: 50%;
            left: -44px;
            width: 10px;
            height: 10px;
            background: #F0C140;
            border: 5px solid #eee9dc;
            border-radius: 50%;
            display: block;
            position: absolute;
        }

        .content {
            max-height: 20px;
            padding: 50px 20px 0;
            border-color: transparent;
            border-width: 2px;
            border-style: solid;
            border-radius: 0.5em;
            position: relative;
        }
        .content:before, .content:after {
            content: "";
            width: 0;
            height: 0;
            border: solid transparent;
            position: absolute;
            pointer-events: none;
            right: 100%;
        }
        .content:before {
            border-right-color: inherit;
            border-width: 20px;
            top: 50%;
            margin-top: -20px;
        }
        .content:after {
            /*border-right-color: #48b379;*/
            border-width: 17px;
            top: 50%;
            margin-top: -17px;
        }
        .content p {
            max-height: 0;
            color: transparent;
            text-align: justify;
            word-break: break-word;
            hyphens: auto;
            overflow: hidden;
        }

        label {
            font-size: 1.3em;
            position: absolute;
            z-index: 100;
            cursor: pointer;
            top: 20px;
            transition: transform 0.2s linear;
        }

        .radio {
            display: none;
        }

        .radio:checked + .relative label {
            cursor: auto;
            transform: translateX(42px);
        }
        .radio:checked + .relative .circle {
            background: #F0C140;
        }
        .radio:checked ~ .content {
            max-height: 100%;
            border-color: #eee9dc;
            margin-right: 20px;
            transform: translateX(20px);
            transition: max-height 0.4s linear, border-color 0.5s linear, transform 0.2s linear;
        }
        .radio:checked ~ .content p {
            max-height: 200px;
            color: #272727;
            transition: color 0.3s linear 0.3s;
        }

        /* -------------------------------------
         * mobile phones (vertical version only)
         * ------------------------------------- */
        @media screen and (max-width: 767px) {
            #timeline {
                margin-left: 0;
                padding-left: 0;
                border-left: none;
            }
            #timeline li {
                margin: 50px 0;
            }

            label {
                width: 85%;
                font-size: 1.1em;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                display: block;
                transform: translateX(18px);
            }

            .content {
                padding-top: 45px;
                border-color: #eee9dc;
            }
            .content:before, .content:after {
                border: solid transparent;
                bottom: 100%;
            }
            .content:before {
                border-bottom-color: inherit;
                border-width: 17px;
                top: -16px;
                left: 50px;
                margin-left: -17px;
            }
            .content:after {
                border-bottom-color: #48b379;
                border-width: 20px;
                top: -20px;
                left: 50px;
                margin-left: -20px;
            }
            .content p {
                font-size: 0.9em;
                line-height: 1.4;
            }

            .circle, .date {
                display: none;
            }
        }

    </style>


    <!--<script src="js/prefixfree.min.js"></script>-->
    <header class="page-header">
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Product Title</span></li>
            </ol>
        </div>
    </header>

    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                A Modern 4 Course Experience at Yauatcha
            </h2>
            <p>Customer Name &nbsp; Location &nbsp; Date/Time</p>
        </header>

        <ul id='timeline'>
            <li class='work'>
                <input class='radio' id='work5' name='works' type='radio' checked>
                <div class="relative">
                    <label for='work5'>Lorem ipsum dolor sit amet</label>
                    <span class='date'>12 May 2013<br/>12.32 pm</span>

                    <span class='circle'></span>
                </div>
                <div class='content'>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio ea necessitatibus quo velit natus cupiditate qui alias possimus ab praesentium nostrum quidem obcaecati nesciunt! Molestiae officiis voluptate excepturi rem veritatis eum aliquam qui laborum non ipsam ullam tempore reprehenderit illum eligendi cumque mollitia temporibus! Natus dicta qui est optio rerum.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio ea necessitatibus quo velit natus cupiditate qui alias possimus ab praesentium nostrum quidem obcaecati nesciunt! Molestiae officiis voluptate excepturi rem veritatis eum aliquam qui laborum non ipsam ullam tempore reprehenderit illum eligendi cumque mollitia temporibus! Natus dicta qui est optio rerum.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio ea necessitatibus quo velit natus cupiditate qui alias possimus ab praesentium nostrum quidem obcaecati nesciunt! Molestiae officiis voluptate excepturi rem veritatis eum aliquam qui laborum non ipsam ullam tempore reprehenderit illum eligendi cumque mollitia temporibus! Natus dicta qui est optio rerum.
                    </p>
                    <p><strong>Note: Buttons would come for actions</strong></p>

                </div>
            </li>
            <p><button class="btn btn-primary">Show Past</button></p>
            <!--<li class='work'>
                <input class='radio' id='work4' name='works' type='radio'>
                <div class="relative">
                    <label for='work4'>Lorem ipsum dolor sit amet</label>
                    <span class='date'>11 May 2013</span>
                    <span class='circle'></span>
                </div>
                <div class='content'>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio ea necessitatibus quo velit natus cupiditate qui alias possimus ab praesentium nostrum quidem obcaecati nesciunt! Molestiae officiis voluptate excepturi rem veritatis eum aliquam qui laborum non ipsam ullam tempore reprehenderit illum eligendi cumque mollitia temporibus! Natus dicta qui est optio rerum.
                    </p>
                </div>
            </li>
            <li class='work'>
              <input class='radio' id='work3' name='works' type='radio'>
              <div class="relative">
                <label for='work3'>Lorem ipsum dolor sit amet</label>
                <span class='date'>10 May 2013</span>
                <span class='circle'></span>
              </div>
              <div class='content'>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio ea necessitatibus quo velit natus cupiditate qui alias possimus ab praesentium nostrum quidem obcaecati nesciunt! Molestiae officiis voluptate excepturi rem veritatis eum aliquam qui laborum non ipsam ullam tempore reprehenderit illum eligendi cumque mollitia temporibus! Natus dicta qui est optio rerum.
                </p>
              </div>
            </li>
            <li class='work'>
              <input class='radio' id='work2' name='works' type='radio'>
              <div class="relative">
                <label for='work2'>Lorem ipsum dolor sit amet</label>
                <span class='date'>09 May 2013</span>
                <span class='circle'></span>
              </div>
              <div class='content'>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio ea necessitatibus quo velit natus cupiditate qui alias possimus ab praesentium nostrum quidem obcaecati nesciunt! Molestiae officiis voluptate excepturi rem veritatis eum aliquam qui laborum non ipsam ullam tempore reprehenderit illum eligendi cumque mollitia temporibus! Natus dicta qui est optio rerum.
                </p>
              </div>
            </li>
            <li class='work'>
              <input class='radio' id='work1' name='works' type='radio'>
              <div class="relative">
                <label for='work1'>Lorem ipsum dolor sit amet</label>
                <span class='date'>08 May 2013</span>
                <span class='circle'></span>
              </div>
              <div class='content'>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio ea necessitatibus quo velit natus cupiditate qui alias possimus ab praesentium nostrum quidem obcaecati nesciunt! Molestiae officiis voluptate excepturi rem veritatis eum aliquam qui laborum non ipsam ullam tempore reprehenderit illum eligendi cumque mollitia temporibus! Natus dicta qui est optio rerum.
                </p>
              </div>
            </li>-->
        </ul>
    </section>
@stop
