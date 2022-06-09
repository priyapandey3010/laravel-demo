<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NCLAT Display Board</title>
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
      
    <div class="border-header-top"></div>
        <section class="header">
          <div class="container">
              <div class="row">
                <div class="col-lg-10 col-md-10 com-sm-12">
                    <div class="brand">
                        <img src="{{ asset('front/img/logonclt.png') }}">
                        <h2>National Company Law Tribunal at New Delhi</h2>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 text-end">
                    <div class="calendar">
                        <p>{{ date('d-m-Y') }} <br> <span>{{ date('l') }}</span></p>
                    </div>
                </div>
              </div>
          </div>
        </section>
    <div class="border-header-bottom"></div>


    <section class="content-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="d-flex justify-content-between" style="margin-bottom:8px;">
                    <h2 class="content-heading">In Session</h2>
                    <a href="{{ url('/') }}" class="btn orange text-white" style="font-weight:500; font-size:14px;">Consolidated List</a>
                    </div>
                    <div class="green"><p class="text-white" style="padding:5px 10px;">{{ $bench }}</p></div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">SN.</th>
                                <th>Court No.</th>
                                <th>Item</th>
                                <th>Case</th>
                                <th>Cause Title</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            @isset($cases)
                                @foreach ($cases as $idx => $case)
                                    
                                        @if ($case['status'] === 'In Session')
                                        <tr style="background-color: 
                                        @if ($case['is_pass_over'])
                                            {{ $case['pass_over_color'] }}
                                        @else
                                            {{ $case['colour_code'] }}
                                        @endif    
                                        ">
                                            <td class="text-white text-center" style="border-left-style: solid; border-top-left-radius: 10px;">{{ $idx+1 }}</td>
                                            <td class="text-white">{{ $case['court_number'] }} ({{ $case['bench_name'] }})</td>
                                            <td class="text-white">{{ $case['item_number'] }}</td>
                                            <td class="text-white">{{ $case['case_number'] }}</td>
                                            <td class="text-white">{{ $case['case_title'] }}</td>
                                            @if ($case['is_pass_over'])
                                                <td class="text-white">{{ $case['pass_over_status'] }}</td>
                                            @else
                                                <td class="text-white">{{ $case['status'] }}</td>
                                            @endif
                                        </tr>
                                        @elseif ($case['status'] === 'N/A') 
                                        <tr>
                                            <td class="text-center" style="border-left-style: solid; border-top-left-radius: 10px;">{{ $idx+1 }}</td>
                                            <td>{{ $case['court_number'] }} ({{ $case['bench_name'] }})</td>
                                            <td class="">{{ $case['item_number'] }}</td>
                                            <td class="">{{ $case['case_number'] }}</td>
                                            <td class="">{{ $case['case_title'] }}</td>
                                            <td class="">{{ $case['status'] }}</td>
                                        </tr>
                                        @else 
                                        <tr style="background-color: {{ $case['colour_code'] }}">
                                            <td class="text-white text-center" style="border-left-style: solid; border-top-left-radius: 10px;">{{ $idx+1 }}</td>
                                            <td class="text-white">{{ $case['court_number'] }} ({{ $case['bench_name'] }})</td>
                                            <td class="text-white">{{ $case['item_number'] }}</td>
                                            <td class="text-white">{{ $case['case_number'] }}</td>
                                            <td class="text-white">{{ $case['case_title'] }}</td>
                                            <td class="text-white">{{ $case['status'] }}</td>
                                        </tr>
                                        @endif
                                        <!-- <td class="text-white">{{ $case['status'] }}</td> -->
                                    </tr>
                                @endforeach
                            @else
                                <tr class="">
                                    <td class="text-dark text-center" colspan="6">No Record Found</td>
                                </tr>
                            @endisset

                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin-theme/vendor/jquery/jquery.min.js') }}"></script>
    <script>
        
        function getDisplayBoardList() {
            $.ajax({
                url: "{{ url('user-display-board/get-court-cases/'. $courtId) }}",
                method: "GET",
                success: function (response) {
                    if (response) {
                        var output = '';
                        var cases = response.data.cases;
                        for (var index in cases) {
                            if (cases[index]['status'] === "{{ __('In Session') }}") {
                                output += `
                                    <tr style="background-color: ${cases[index]['is_pass_over'] ? cases[index]['pass_over_color'] : cases[index]['colour_code']}">
                                        <td class="text-white text-center" style="border-left-style: solid; border-top-left-radius: 10px;">${+index+1}</td>
                                        <td class="text-white">${cases[index]['court_number']} (${cases[index]['bench_name']})</td> 
                                        <td class="text-white">${cases[index]['item_number']}</td>
                                        <td class="text-white">${cases[index]['case_number']}</td>
                                        <td class="text-white">${cases[index]['case_title']}</td>
                                        <td class="text-white">${cases[index]['is_pass_over'] ? cases[index]['pass_over_status'] : cases[index]['status']}</td>
                                    </tr> 
                                `;
                            } 
                            else if ($case['status'] === 'N/A') {
                                $output += `<tr style="">
                                        <td class="text-center" style="border-left-style: solid; border-top-left-radius: 10px;">${+index+1}</td>
                                        <td>${cases[index]['court_number']} (${cases[index]['bench_name']})</td> 
                                        <td>${cases[index]['item_number']}</td>
                                        <td>${cases[index]['case_number']}</td>
                                        <td>${cases[index]['case_title']}</td>
                                        <td>${cases[index]['status']}</td>
                                    </tr> `;
                            }
                            else {
                                output += `
                                    <tr style="background-color: ${cases[index]['colour_code']}">
                                        <td class="text-white text-center" style="border-left-style: solid; border-top-left-radius: 10px;">${+index+1}</td>
                                        <td class="text-white">${cases[index]['court_number']} (${cases[index]['bench_name']})</td> 
                                        <td class="text-white">${cases[index]['item_number']}</td>
                                        <td class="text-white">${cases[index]['case_number']}</td>
                                        <td class="text-white">${cases[index]['case_title']}</td>
                                        <td class="text-white">${cases[index]['status']}</td>
                                        
                                    </tr> 
                                `;
                            }
                        }
                        $("#tbody").html(output);
                    }
                }
            });
        }

        setInterval(getDisplayBoardList, 5000);
    </script>
  </body>
</html>